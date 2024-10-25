<?php

declare(strict_types=1);

namespace test\Concerto\template;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\template\{
    CurlyBracketMessageGenerator,
    PrintfMessageGenerator,
    TemplateObject
};

class TemplateObjectTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function byPrintfMessageGenerator()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TemplateObject(
            new PrintfMessageGenerator()
        );

        $this->assertEquals(
            '',
            $this->getPrivateProperty($obj, 'template')
        );

        $template = 'new string=%s\\n';
        $expect = $template;
        $obj->append($template);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'template')
        );

        //append
        $template = 'append string=%s\\n';
        $expect = "{$expect}{$template}";
        $obj->append($template);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'template')
        );

        //prepend
        $template = 'prepend string=%s\\n';
        $expect = "{$template}{$expect}";
        $obj->prepend($template);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'template')
        );

        //toString before apply
        $this->assertEquals(
            $expect,
            $obj->toString()
        );

        //toString after apply
        $data = ['PREPEND', 'NEW', 'APPEND'];
        $obj->apply($data);
        $expect = vsprintf($expect, $data);
        $this->assertEquals(
            $expect,
            $obj->toString()
        );

        //toString re apply
        $data = ['PREPEND', 'NEW', 'APPEND'];
        $obj->apply($data);
        $expect = $expect;
        $this->assertEquals(
            $expect,
            $obj->toString()
        );
    }

    /**
    */
    #[Test]
    public function byCurlyBracketMessageGenerator()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TemplateObject(
            new CurlyBracketMessageGenerator()
        );

        $this->assertEquals(
            '',
            $this->getPrivateProperty($obj, 'template')
        );

        $template = 'new string={{new}}\\n';
        $expect = $template;
        $obj->append($template);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'template')
        );

        //append
        $template = 'append string={{append}}\\n';
        $expect = "{$expect}{$template}";
        $obj->append($template);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'template')
        );

        //prepend
        $template = 'prepend string={{prepend}}\\n';
        $expect = "{$template}{$expect}";
        $obj->prepend($template);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'template')
        );

        //toString before apply
        $this->assertEquals(
            $expect,
            $obj->toString()
        );

        //toString after apply
        $data = [
            'prepend' => 'PREPEND',
            'new' => 'NEW',
            'append' => 'APPEND'
        ];
        $obj->apply($data);
        $expect =
            'prepend string=PREPEND\\n' .
            'new string=NEW\\n' .
            'append string=APPEND\\n';

        $this->assertEquals(
            $expect,
            $obj->toString()
        );

        //toString re apply
        $data = ['PREPEND', 'NEW', 'APPEND'];
        $obj->apply($data);
        $expect = $expect;
        $this->assertEquals(
            $expect,
            $obj->toString()
        );
    }
}
