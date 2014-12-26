<?php

require_once '../index.php';

class ParsingTest extends PHPUnit_Framework_TestCase
{

    public function testQueryId()
    {
        $this->start = new Parsing;
        $this->assertInternalType('array', $this->start->queryId());
    }
    
    public function testQueryContent()
    {
        $this->start = new Parsing;
        $this->assertInternalType('string', $this->start->queryContent("13496"));
    }
    
    public function testAnalyzeName()
    {
        $this->start = new Parsing;
        $this->assertInternalType('array', $this->start->analyzeName());
    }
}