<?php

class wkb extends PHPUnit_Framework_TestCase
{
    private $decoder = null;
    
    public function setup()
    {
        if (!$this->decoder) {
            $this->decoder = new Symm\Gisconverter\Decoders\WKB();
        }
    }
    
    /**
     * @expectedException Symm\Gisconverter\Exceptions\InvalidText
     */
    public function testInvalidText1()
    {
        $this->decoder->geomFromBinary('0104000000000000', true);
    }
    
    /**
     * @expectedException Symm\Gisconverter\Exceptions\InvalidText
     */
    public function testInvalidText2()
    {
        $this->decoder->geomFromBinary('00000000000000000000000000000000000000000', true);
    }
    
    public function testPoint()
    {
        
        // POINT(10 10)
        $geom = $this->decoder->geomFromBinary('010100000000000000000024400000000000002440', true);
        $this->assertEquals($geom->toWKB(true), '010100000000000000000024400000000000002440');
        
        // POINT(0 0)
        $geom = $this->decoder->geomFromBinary('010100000000000000000000000000000000000000', true);
        $this->assertEquals($geom->toWKB(true), '010100000000000000000000000000000000000000');
    }
    
    public function testMultiPoint()
    {
        
        // 'MULTIPOINT(3.5 5.6,4.8 10.5,10 10)'
        $geom = $this->decoder->geomFromBinary('01040000000300000001010000000000000000000c406666666666661640010100000033333333333313400000000000002540010100000000000000000024400000000000002440', true);
        $this->assertEquals($geom->toWKB(true), '01040000000300000001010000000000000000000c406666666666661640010100000033333333333313400000000000002540010100000000000000000024400000000000002440');
        
        // MULTIPOINT()
        $geom = $this->decoder->geomFromBinary('010400000000000000', true);
        $this->assertEquals($geom->toWKB(true), '010400000000000000');
    }
    
    public function testLineString()
    {
        
        // LINESTRING(3.5 5.6,4.8 10.5,10 10)
        $geom = $this->decoder->geomFromBinary('0102000000030000000000000000000c4066666666666616403333333333331340000000000000254000000000000024400000000000002440', true);
        $this->assertEquals($geom->toWKB(true), '0102000000030000000000000000000c4066666666666616403333333333331340000000000000254000000000000024400000000000002440');
    }
    
    public function testMultiLineString()
    {
        
        // MULTILINESTRING((3.5 5.6,4.8 10.5,10 10))
        $geom = $this->decoder->geomFromBinary('0105000000010000000102000000030000000000000000000c4066666666666616403333333333331340000000000000254000000000000024400000000000002440', true);
        $this->assertEquals($geom->toWKB(true), '0105000000010000000102000000030000000000000000000c4066666666666616403333333333331340000000000000254000000000000024400000000000002440');
        
        // MULTILINESTRING((3.5 5.6,4.8 10.5,10 10),(10 10,10 20,20 20,20 15))
        $geom = $this->decoder->geomFromBinary('0105000000020000000102000000030000000000000000000c406666666666661640333333333333134000000000000025400000000000002440000000000000244001020000000400000000000000000024400000000000002440000000000000244000000000000034400000000000003440000000000000344000000000000034400000000000002e40', true);
        $this->assertEquals($geom->toWKB(true), '0105000000020000000102000000030000000000000000000c406666666666661640333333333333134000000000000025400000000000002440000000000000244001020000000400000000000000000024400000000000002440000000000000244000000000000034400000000000003440000000000000344000000000000034400000000000002e40');
        
        // MULTILINESTRING()
        $geom = $this->decoder->geomFromBinary('010500000000000000', true);
        $this->assertEquals($geom->toWKB(true), '010500000000000000');
    }
    
    public function testLinearRing()
    {
        
        // LINEARRING(3.5 5.6,4.8 10.5,10 10,3.5 5.6)
        $geom = $this->decoder->geomFromBinary('0102000000040000000000000000000c40666666666666164033333333333313400000000000002540000000000000244000000000000024400000000000000c406666666666661640', true);
        $this->assertEquals($geom->toWKB(true), '0102000000040000000000000000000c40666666666666164033333333333313400000000000002540000000000000244000000000000024400000000000000c406666666666661640');
    }
    
    public function testPolygon()
    {
        
        // POLYGON((10 10,10 20,20 20,20 15,10 10))
        $geom = $this->decoder->geomFromBinary('0103000000010000000500000000000000000024400000000000002440000000000000244000000000000034400000000000003440000000000000344000000000000034400000000000002e4000000000000024400000000000002440', true);
        $this->assertEquals($geom->toWKB(true), '0103000000010000000500000000000000000024400000000000002440000000000000244000000000000034400000000000003440000000000000344000000000000034400000000000002e4000000000000024400000000000002440');
        
        // POLYGON((0 0,10 0,10 10,0 10,0 0),(1 1,9 1,9 9,1 9,1 1))
        $geom = $this->decoder->geomFromBinary('01030000000200000005000000000000000000000000000000000000000000000000002440000000000000000000000000000024400000000000002440000000000000000000000000000024400000000000000000000000000000000005000000000000000000f03f000000000000f03f0000000000002240000000000000f03f00000000000022400000000000002240000000000000f03f0000000000002240000000000000f03f000000000000f03f', true);
        $this->assertEquals($geom->toWKB(true), '01030000000200000005000000000000000000000000000000000000000000000000002440000000000000000000000000000024400000000000002440000000000000000000000000000024400000000000000000000000000000000005000000000000000000f03f000000000000f03f0000000000002240000000000000f03f00000000000022400000000000002240000000000000f03f0000000000002240000000000000f03f000000000000f03f');
    }
    
    public function testMultiPolygon()
    {
        
        // MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)))
        $geom = $this->decoder->geomFromBinary('0106000000010000000103000000010000000500000000000000000024400000000000002440000000000000244000000000000034400000000000003440000000000000344000000000000034400000000000002e4000000000000024400000000000002440', true);
        $this->assertEquals($geom->toWKB(true), '0106000000010000000103000000010000000500000000000000000024400000000000002440000000000000244000000000000034400000000000003440000000000000344000000000000034400000000000002e4000000000000024400000000000002440');
        
        // MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)),((60 60,70 70,80 60,60 60)))
        
        $geom = $this->decoder->geomFromBinary('0106000000020000000103000000010000000500000000000000000024400000000000002440000000000000244000000000000034400000000000003440000000000000344000000000000034400000000000002e4000000000000024400000000000002440010300000001000000040000000000000000004e400000000000004e400000000000805140000000000080514000000000000054400000000000004e400000000000004e400000000000004e40', true);
        $this->assertEquals($geom->toWKB(true), '0106000000020000000103000000010000000500000000000000000024400000000000002440000000000000244000000000000034400000000000003440000000000000344000000000000034400000000000002e4000000000000024400000000000002440010300000001000000040000000000000000004e400000000000004e400000000000805140000000000080514000000000000054400000000000004e400000000000004e400000000000004e40');
        
        // MULTIPOLYGON()
        $geom = $this->decoder->geomFromBinary('010600000000000000', true);
        $this->assertEquals($geom->toWKB(true), '010600000000000000');
    }
    
    public function testGeometryCollection()
    {
        
        // GEOMETRYCOLLECTION(POINT(10 10),POINT(30 30),LINESTRING(15 15,20 20))
        $geom = $this->decoder->geomFromBinary('01070000000300000001010000000000000000002440000000000000244001010000000000000000003e400000000000003e400102000000020000000000000000002e400000000000002e4000000000000034400000000000003440', true);
        $this->assertEquals($geom->toWKB(true), '01070000000300000001010000000000000000002440000000000000244001010000000000000000003e400000000000003e400102000000020000000000000000002e400000000000002e4000000000000034400000000000003440');
        
        // GEOMETRYCOLLECTION(POINT(10 10),POINT(30 30),LINESTRING(15 15,20 20))
        $geom = $this->decoder->geomFromBinary('010700000000000000', true);
        $this->assertEquals($geom->toWKB(true), '010700000000000000');
    }
}
