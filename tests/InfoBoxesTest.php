<?php

class InfoBoxesTest extends SapphireTest
{

    public function testParseForJS()
    {
        $reflection = new ReflectionClass('InfoBoxes');
        $method = $reflection->getMethod('parseForJS');
        $method->setAccessible(true);

        $checks = new InfoBoxes();

        $testConf = array(
            'dev' => array(
                'type' => 0,
                'message' => 'Dev Mode',
                'link' => 'http://google.co.uk/'
            ),
            'pass' => array(
                'type' => 1,
                'message' => 'Default Password',
                'link' => false
            ),
            'html' => array(
                'type' => 2,
                'message' => '<style>.devcheck { font-size: 18px; }</style><strong>\'HTML\' "Test"</strong>',
                'link' => '"><script>alert(\'ok\');</script><a href="#'
            )
        );

        $returned = $method->invoke($checks, $testConf);
        $expected = array(
            'Data' => "[[0, 'Dev Mode', 'http://google.co.uk/']," .
            " [1, 'Default Password', ''], " .
            "[2, '&lt;style&gt;.devcheck { font-size: 18px; }&lt;/style&gt;&lt;strong&gt;&#039;HTML&#039; &quot;Test&quot;&lt;/strong&gt;', " .
            "'&quot;&gt;&lt;script&gt;alert(&#039;ok&#039;);&lt;/script&gt;&lt;a href=&quot;#']]"
        );

        $this->assertEquals($expected, $returned);
    }

    public function testEscapeJS()
    {
        $reflection = new ReflectionClass('InfoBoxes');
        $method = $reflection->getMethod('escapeJS');
        $method->setAccessible(true);

        $checks = new InfoBoxes();

        $returned = $method->invoke($checks, "' <script>alert('hello');</script>");
        $expected = "&#039; &lt;script&gt;alert(&#039;hello&#039;);&lt;/script&gt;";

        $this->assertEquals($expected, $returned);
    }
}
