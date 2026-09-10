<?php

function imgupload($image, $imgaddress, $format)
{
    $imageName = time() . '.' . $image->getClientOriginalExtension();
    $image->move(public_path($imgaddress), $imageName);
    return $imageName;
}