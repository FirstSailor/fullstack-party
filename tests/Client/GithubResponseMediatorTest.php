<?php
/**
 * Created by PhpStorm.
 * User: rokas
 * Date: 18.1.29
 * Time: 19.49
 */

namespace App\Tests\Client;

use App\Client\GithubResponseMediator;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class GithubResponseMediatorTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;

    /**
     * @var GithubResponseMediator
     */
    private $mediator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->mediator = new GithubResponseMediator($this->serializer);
    }

    /**
     * @test
     * @dataProvider getPaginationDataProvider
     * @param ResponseInterface $response
     * @param $expectedOutput
     */
    public function testGetPaginationData(ResponseInterface $response, $expectedOutput)
    {
        $this->assertSame($expectedOutput, $this->mediator->getPaginationData($response));
    }

    /**
     * @return array
     */
    public function getPaginationDataProvider(): array
    {
        $response1 = $this->getResponseMock();
        $response1->method('hasHeader')->willReturn(false);

        $response2 = $this->getResponseMock();
        $response2->method('hasHeader')->willReturn(true);
        $response2->method('getHeader')->willReturn([
            '<https://api.github.com/user/repos?page=3&per_page=100>; rel="next",' .
            '<https://api.github.com/user/repos?page=50&per_page=100>; rel="last"'
        ]);

        $response3 = $this->getResponseMock();
        $response3->method('hasHeader')->willReturn(true);
        $response3->method('getHeader')->willReturn([
            '<https://api.github.com/user/repos?page=3&per_page=100>; rel="next",' .
            '<https://api.github.com/user/repos?page=50&per_page=100>; rel="prev"'
        ]);

        return [
            [$response1, []],
            [
                $response2,
                [
                    'next' => 'https://api.github.com/user/repos?page=3&per_page=100',
                    'last' => 'https://api.github.com/user/repos?page=50&per_page=100',
                ]
            ],
            [
                $response3,
                [
                    'next' => 'https://api.github.com/user/repos?page=3&per_page=100',
                    'prev' => 'https://api.github.com/user/repos?page=50&per_page=100',
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getTotalPagesCountProvider
     * @param ResponseInterface $response
     * @param $expectedOutput
     */
    public function testGetTotalPagesCount(ResponseInterface $response, $expectedOutput)
    {
        $this->assertSame($expectedOutput, $this->mediator->getTotalPagesCount($response));
    }

    /**
     * @return array
     */
    public function getTotalPagesCountProvider(): array
    {

        $response1 = $this->getResponseMock();
        $response1->method('hasHeader')->willReturn(false);

        $response2 = $this->getResponseMock();
        $response2->method('hasHeader')->willReturn(true);
        $response2->method('getHeader')->willReturn([
            '<https://api.github.com/user/repos?page=3&per_page=100>; rel="next",' .
            '<https://api.github.com/user/repos?page=50&per_page=100>; rel="last"'
        ]);

        $response3 = $this->getResponseMock();
        $response3->method('hasHeader')->willReturn(true);
        $response3->method('getHeader')->willReturn([
            '<https://api.github.com/user/repos?page=3&per_page=100>; rel="next",' .
            '<https://api.github.com/user/repos?page=50&per_page=100>; rel="prev"'
        ]);

        return [
            [$response1, null],
            [$response2, 50],
            [$response3, 51],
        ];
    }

    /**
     * @test
     * @dataProvider getHeaderProvider
     * @param ResponseInterface $response
     * @param string $name
     * @param $expectedOutput
     */
    public function testGetHeader(ResponseInterface $response, string $name, $expectedOutput)
    {
        $this->assertSame($expectedOutput, $this->mediator->getHeader($response, $name));
    }

    /**
     * @return array
     */
    public function getHeaderProvider(): array
    {
        $response1 = $this->getResponseMock();
        $response1->method('getHeader')->willReturn(['header1', 'header2']);

        $response2 = $this->getResponseMock();
        $response2->method('getHeader')->willReturn([]);

        return [
            [$response1, 'test', 'header1'],
            [$response2, 'test', null],
        ];
    }

    /**
     * @return MockObject|ResponseInterface
     */
    private function getResponseMock()
    {
        return $this->createMock(ResponseInterface::class);
    }
}
