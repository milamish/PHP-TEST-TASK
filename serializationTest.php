<?php
use PHPUnit\Framework\TestCase;

class SerializationTest extends TestCase {
    
    private $configFilePath;
    private $serializedFilePath;
    
    protected function setUp(): void {
        $this->configFilePath = 'config.php';
        $this->serializedFilePath = 'serializeddata.dat';
    }

    public function testSerialization() {
    // Arrange
    $originalData = ['key' => 'value'];

    // Act
    file_put_contents($this->configFilePath, '<?php return ' . var_export($originalData, true) . ';');
    $largeData = include $this->configFilePath; // Set $largeData with the included data

    // Assert
    $this->assertEquals($originalData, $largeData);
    }
    
    public function testFileExistence() {
    // Arrange
    $nonExistentConfigFilePath = 'nonexistent.json';

    // Act & Assert
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage("Config file $nonExistentConfigFilePath not found");

    // Use require instead of include to trigger a fatal error if the file is not found
    require 'serialization.php';
    }


    public function testSerializationAndCompression() {
        // Arrange
        $originalData = ['key' => 'value'];

        // Act
        file_put_contents($this->configFilePath, '<?php return ' . var_export($originalData, true) . ';');
        include $this->configFilePath;
        include 'serialization.php';

        // Assert
        $this->assertFileExists($this->serializedFilePath);
        $this->assertGreaterThan(0, filesize($this->serializedFilePath));
    }

    public function testDecompressionAndUnserialization() {
        // Arrange
        $originalData = ['key' => 'value'];

        // Act
        file_put_contents($this->configFilePath, '<?php return ' . var_export($originalData, true) . ';');
        include $this->configFilePath;
        include 'serialization.php';

        // Assert
        $this->assertFileExists($this->serializedFilePath);
        $this->assertGreaterThan(0, filesize($this->serializedFilePath));

        // Act & Assert
        ob_start();
        include 'serialization.php';
        $output = ob_get_clean();
        $this->assertStringContainsString(print_r($originalData, true), $output);
    }

}
?> 

