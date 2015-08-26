<?php

printf('{
    "msg": %s,
    "file": %s:%d,
    "trace": %s
}', $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());