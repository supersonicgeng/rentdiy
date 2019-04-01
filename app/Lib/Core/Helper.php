<?php
function makeElUniqueName($el) {
    return 'b'.md5(\Route::current()->uri().$el);
}

function isTrue($variant) {
    if (isset($variant)) {
        return $variant;
    }
    return false;
}
