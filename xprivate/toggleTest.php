<?php
    $newfile = '../hackathon_test/index.html';
    $state = file_get_contents('test_state.txt');
    if($state == 'on'){
        $new_state = 'off';
        $file = '../off/index.html';
    } else {
        $new_state = 'on';
        $file = '../on/index.html';
    }
    copy($file, $newfile);
    file_put_contents('test_state.txt', $new_state);

    echo $new_state;