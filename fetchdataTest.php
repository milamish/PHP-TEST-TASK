<?php
// DatabaseManagerTest.php
use PHPUnit\Framework\TestCase;

class FetcchdataTest extends TestCase {
    private $databaseManager;

    protected function setUp(): void {
        $serverName = "test-server";
        $connectionOptions = array(
            "Database" => "test",
            "Uid" => "test",
            "PWD" => "pswd",
            "Encrypt" => true,
            "TrustServerCertificate" => false,
        );

        $this->databaseManager = new DatabaseManager($serverName, $connectionOptions);
    }

    public function testFetchData() {
        // Using PHPUnit mocks to mock the database connection and result
        $mockQueryResult = $this->createMock('QueryResultClass');
        $mockQueryResult->method('fetch_array')
            ->willReturnOnConsecutiveCalls(['CustomerID' => 1, 'CustomerName' => 'Test', 'TotalSpent' => 100, 'TotalPaid' => 50, 'delivery_address' => 'beijing road, Syokimau'], false);

        $mockDatabaseManager = $this->getMockBuilder(DatabaseManager::class)
            ->onlyMethods(['fetchData'])
            ->getMock();
        
        $mockDatabaseManager->method('fetchData')
            ->willReturn($mockQueryResult);

        // Perform the test
        $result = $mockDatabaseManager->fetchData();

        // Assertion that the result is as expected
        $expectedResult = [
            ['CustomerID' => 1, 'CustomerName' => 'Test', 'TotalSpent' => 100, 'TotalPaid' => 50, 'delivery_address' => 'beijing road, Syokimau']
        ];

        $this->assertEquals($expectedResult, $result);
    }

    protected function tearDown(): void {
        // Close connection after each test
        $this->databaseManager->closeConnection();
    }
}

?>

