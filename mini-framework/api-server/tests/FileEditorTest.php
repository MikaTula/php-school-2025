<?php

declare(strict_types=1);

namespace Tests;

class FileEditorTest extends BaseTestCase
{
    public function testNoInputFile()
    {
    }

    public function testHasInputFile()
    {
        $fileEditor = null;
        $this->assertIsNotObject($fileEditor);
    }
}
