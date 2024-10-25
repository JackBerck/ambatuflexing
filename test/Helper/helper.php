<?php

namespace JackBerck\Ambatuflexing\App {

    function header(string $value)
    {
        echo $value;
    }
}

namespace JackBerck\Ambatuflexing\Service {

    function setcookie(string $name, string $value)
    {
        echo "$name: $value";
    }
}
