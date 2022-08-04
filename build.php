<?php

if ($argc < 2) {
    echo "Missing version number\n";
    exit(1);
}

$match = preg_match('/^([0-9]+)\.([0-9]+)\.([0-9]+)(?:-([0-9A-Za-z-]+(?:\.[0-9A-Za-z-]+)*))?(?:\+[0-9A-Za-z-]+)?$/', $argv[1]);

if ($match === 0) {
    echo "Version does not match SemVer conventions\n";
    exit(1);
}

const OS_KEY = 'os';
const ARCH_KEY = 'arch';
const FILE_EXT_KEY = 'ext';

$dists = shell_exec('go tool dist list');
$dists = explode("\n", $dists);
$dirname = dirname(__FILE__);
$dirname_split = explode('/', $dirname);
$dirname = $dirname_split[count($dirname_split) - 1];

$clean_dists = [];
foreach ($dists as $dist) {
    $split = explode('/', $dist);

    $os = $split[0];
    $arch = $split[1];
    $file_ext = null;

    if (!in_array($os, ['linux', 'darwin', 'windows', 'js']) || !in_array($arch, ['386', 'amd64', 'arm64', 'arm', 'wasm'])) {
        continue;
    }

    if ($os == 'windows') {
        $file_ext = 'exe';
    }

    if ($os == 'js') {
        $file_ext = 'wasm';
    }

    $clean_dists[] = [
        OS_KEY => $os,
        ARCH_KEY => $arch,
        FILE_EXT_KEY => $file_ext,
    ];
}

foreach ($clean_dists as $dist) {
    $os = $dist[OS_KEY];
    $arch = $dist[ARCH_KEY];
    $file_ext = $dist[FILE_EXT_KEY];
    $version = $argv[1];

    $filename = "dist/$dirname--{$os}-{$arch}--{$version}";
    if ($file_ext) {
        $filename .= ".$file_ext";
    }

    echo "Building $filename: ";
    exec("env CGO_ENABLED=1 GOOS=$os GOARCH=$arch go build -o $filename", $out, $res);
    if ($res !== 0) {
        echo "FAIL\n";
        exit($res);
    }
    echo "DONE\n";
}