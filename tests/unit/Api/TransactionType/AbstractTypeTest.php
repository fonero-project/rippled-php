<?php

namespace FOXRP\Rippled\Tests\Api\TransactionType;

use PHPUnit\Framework\TestCase;
use FOXRP\Rippled\Api\Field;
use FOXRP\Rippled\Exception\InvalidParameterException;
use FOXRP\Rippled\Api\Transaction;
use FOXRP\Rippled\Api\TransactionType\Payment;

/**
*  Test for Client class
*/
class AbstractTypeTest extends TestCase
{
    /** @var Transaction */
    private $tx;

    public function setUp()
    {
        $this->tx = new Transaction($this->getTx());
    }

    /**
    * Check that we can find the Payment class.
    */
    public function testGetField(): void
    {
        /** @var Payment $type */
        $type = $this->tx->getType();
        $field = $type->getField('TransactionType');
        $this->assertEquals(Field::class, get_class($field));
    }

    /**
     * Check that we can get required fields.
     */
    public function testGetRequiredFields(): void
    {
        $type = $this->tx->getType();
        $fields = $type->getRequiredFields();
        $this->assertCount(4, $fields);
    }

    /**
     * Check missing param throws exception.
     */
    public function testValidateParamsMissingThrowsException(): void
    {
        $this->expectException(InvalidParameterException::class);
        $this->expectExceptionMessageRegExp('/Account/');

        $params = $this->tx->getTx();
        unset($params['Account']);
        new Payment($params);
    }

    /**
     * Check invalid param throws exception.
     */
    public function testValidateParamsInvalidThrowsException(): void
    {
        $this->expectException(InvalidParameterException::class);
        $this->expectExceptionMessageRegExp('/Foo/');

        $params = $this->tx->getTx();
        $params['Foo'] = 'Bar';
        new Payment($params);
    }

    private function getTx()
    {
        return [
            'TransactionType' => 'Payment',
            'Account' => 'rQBnNY5w5cALHbMaue2VefSzuBfxafwqp9',
            'Destination' => 'rnQ1WgToG2RL9Fjmofif9ixYVgJTi6BLas',
            'Amount' => '1000000',
            'Fee' => '000012'
        ];
    }
}
