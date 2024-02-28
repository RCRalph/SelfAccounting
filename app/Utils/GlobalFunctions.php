<?php

function getFileLink($disk, $directory, $filename): ?string
{
    switch ($disk) {
        case "s3":
            $endpoint = config("filesystems.disks.s3.endpoint");
            $bucket = config("filesystems.disks.s3.bucket");

            return "$endpoint/$bucket/$directory/$filename";

        case "public":
            return "/storage/$directory/$filename";

        default:
            return null;
    }
}
