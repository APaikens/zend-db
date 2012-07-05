<?php
namespace Zend\Db\Sql;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-03-01 at 23:40:12.
 */
class DeleteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Delete
     */
    protected $delete;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->delete = new Delete;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Zend\Db\Sql\Delete::from
     */
    public function testFrom()
    {
        $this->delete->from('foo', 'bar');
        $this->assertEquals('foo', $this->readAttribute($this->delete, 'table'));
    }

    /**
     * @covers Zend\Db\Sql\Delete::where
     */
    public function testWhere()
    {
        $this->delete->where('x = y');
        $this->delete->where(array('foo > ?' => 5));
        $this->delete->where(array('id' => 2));
        $this->delete->where(array('a = b'), Where::OP_OR);
        $where = $this->delete->where;

        $predicates = $this->readAttribute($where, 'predicates');
        $this->assertEquals('AND', $predicates[0][0]);
        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Expression', $predicates[0][1]);

        $this->assertEquals('AND', $predicates[1][0]);
        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Expression', $predicates[1][1]);

        $this->assertEquals('AND', $predicates[2][0]);
        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Operator', $predicates[2][1]);

        $this->assertEquals('OR', $predicates[3][0]);
        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Expression', $predicates[3][1]);

        $where = new Where;
        $this->delete->where($where);
        $this->assertSame($where, $this->delete->where);

        $test = $this;
        $this->delete->where(function ($what) use ($test, $where) {
            $test->assertSame($where, $what);
        });
    }

    /**
     * @covers Zend\Db\Sql\Delete::prepareStatement
     */
    public function testPrepareStatement()
    {
        $mockDriver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
        $mockAdapter = $this->getMock('Zend\Db\Adapter\Adapter', null, array($mockDriver));

        $mockStatement = $this->getMock('Zend\Db\Adapter\Driver\StatementInterface');
        $mockStatement->expects($this->at(2))
            ->method('setSql')
            ->with($this->equalTo('DELETE FROM "foo" WHERE x = y'));

        $this->delete->from('foo')
            ->where('x = y');

        $this->delete->prepareStatement($mockAdapter, $mockStatement);
    }

    /**
     * @covers Zend\Db\Sql\Delete::getSqlString
     */
    public function testGetSqlString()
    {
        $this->delete->from('foo')
            ->where('x = y');
        $this->assertEquals('DELETE FROM "foo" WHERE x = y', $this->delete->getSqlString());

    }
}
