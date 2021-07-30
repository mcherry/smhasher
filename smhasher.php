#!/usr/bin/php
<?php
echo "Smhasher - Now with more HASH!\n".
     "Written by Mike Cherry <mcherry@inditech.org>\n\n";

$opts = getopt("h:w:a");
if (!count($opts)) {
    die("Usage: ".$argv[0]." [-a] -h <hash> -w <wordlist>\n\n".
        "-a\t\tDisplay supported hashing algorithms and exit.\n".
        "-h <hash>\tThe value to be smhashed. Smhasher will attempt to guess the algorithm that was used.\n".
        "-w <wordlist>\tA plain text file containing the passwords to check with one password per line.\n\n");
}
if (isset($opts['a'])) die("Supported algorithms: "._hashes()."\n\n");

foreach (hash_algos() as $name) {
    if (strlen($opts['h']) == strlen(hash($name, $name, false))) $hashes[] = $name;
}
if (!count($hashes)) die("No matching algorithms found! Are you sure you provided a hashed value?\n\n");

echo "Provided hash:       ".$opts['h']."\n".
     "Matching algorithms: "._hashes($hashes)."\n\n";

if ($file = @fopen($opts['w'], 'r')) {
    while (($line = @fgets($file)) !== false) {
        if (($line = trim($line))) {
            foreach ($hashes as $hash) {
                if ($opts['h'] == hash($hash, $line, false)) {
                    echo "Found (".$hash."): ".$line."\n\n";
                    @fclose($file);
                    exit;
                }
            }
        }
    }

    @fclose($file);
    die("No matches found.\n\n");
}
die("Unable to open wordlist!\n\n");

function _hashes($hashes = array()) {
    if (!count($hashes)) $hashes = hash_algos();
    foreach ($hashes as $hash) $hash_names .= str_replace(",", "-", $hash).", ";
    return substr($hash_names, 0, -2);
}
?>
