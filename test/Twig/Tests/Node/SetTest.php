<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

class Twig_Tests_Node_SetTest extends Twig_Test_NodeTestCase
{
    /**
     * @covers Twig_Node_Set::__construct
     */
    public function testConstructor()
    {
        $names = new Twig_Node(array(new Twig_Node_Expression_AssignName('foo', 1)), array(), 1);
        $values = new Twig_Node(array(new Twig_Node_Expression_Constant('foo', 1)), array(), 1);
        $node = new Twig_Node_Set(false, $names, $values, 1);

        $this->assertEquals($names, $node->getNode('names'));
        $this->assertEquals($values, $node->getNode('values'));
        $this->assertEquals(false, $node->getAttribute('capture'));
    }

    /**
     * @covers Twig_Node_Set::compile
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);
    }

    public function getTests()
    {
        $tests = array();

        $names = new Twig_Node(array(new Twig_Node_Expression_AssignName('foo', 1)), array(), 1);
        $values = new Twig_Node(array(new Twig_Node_Expression_Constant('foo', 1)), array(), 1);
        $node = new Twig_Node_Set(false, $names, $values, 1);
        $tests[] = array($node, <<<EOF
// line 1
\$context["foo"] = "foo";
EOF
        );

        $names = new Twig_Node(array(new Twig_Node_Expression_AssignName('foo', 1)), array(), 1);
        $values = new Twig_Node(array(new Twig_Node_Print(new Twig_Node_Expression_Constant('foo', 1), 1)), array(), 1);
        $node = new Twig_Node_Set(true, $names, $values, 1);
        $tests[] = array($node, <<<EOF
// line 1
ob_start();
echo "foo";
\$context["foo"] = ('' === \$tmp = ob_get_clean()) ? '' : new Twig_Markup(\$tmp, \$this->env->getCharset());
EOF
        );

        $names = new Twig_Node(array(new Twig_Node_Expression_AssignName('foo', 1)), array(), 1);
        $values = new Twig_Node_Text('foo', 1);
        $node = new Twig_Node_Set(true, $names, $values, 1);
        $tests[] = array($node, <<<EOF
// line 1
\$context["foo"] = ('' === \$tmp = "foo") ? '' : new Twig_Markup(\$tmp, \$this->env->getCharset());
EOF
        );

        $names = new Twig_Node(array(new Twig_Node_Expression_AssignName('foo', 1), new Twig_Node_Expression_AssignName('bar', 1)), array(), 1);
        $values = new Twig_Node(array(new Twig_Node_Expression_Constant('foo', 1), new Twig_Node_Expression_Name('bar', 1)), array(), 1);
        $node = new Twig_Node_Set(false, $names, $values, 1);
        $tests[] = array($node, <<<EOF
// line 1
list(\$context["foo"], \$context["bar"]) = array("foo", {$this->getVariableGetter('bar')});
EOF
        );

        return $tests;
    }
}
