<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('tv-channel', function () {
    return true; // publik
});
