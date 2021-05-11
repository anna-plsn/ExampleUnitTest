<?php
//название файла теста всегда должно содержать суффикс, указанный в файле phpunit.php

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    //хранение объекта для тестирования
    private $user;

    //вызывается до теста
    protected function setUp(): void
    {
        $this->user = new App\Models\User();
        $this->user->setName("Леша");
        $this->user->setPassword("123");
    }

    //вызывается после теста
    protected function tearDown(): void
    {

    }

    //тестирование метода setName()
    public function testName() {

        //проверка равенства двух значений. $this->user->getName() = "Леша"
        $this->assertEquals("Леша", $this->user->getName());
    }

    //тестирование метода setName() с указанием в аннотации провайдера данных
    /**
     * @dataProvider passwordProvider
     */
    public function testPassword($password) {

        //проверка равенства двух значений. $this->user->getPassword() = 123
        $this->assertEquals($password, $this->user->getPassword());
    }

    //провайдер - итератор набора тестов для testPassword()
    public function passwordProvider(): array
    {
        return [[1], [2], [123]];
    }

    //тесты, зависящие друг от друга
    /**
     * @depends testEmailDependence
     */
    public function testNameDependence($name) {

        //проверка равенства двух значений. $this->user->getName() = "Леша"
        $this->assertEquals($name . "@mail.ru", $this->user->getName() . "@mail.ru");
    }

    public function testEmailDependence(): string
    {

        //проверка равенства двух значений. $this->user->getName() = "Леша"
        $this->assertEquals("Леша", $this->user->getName());
        return "Леша";
    }

    //обработка исключений (проверка работы исключения, тест завершится успешно в случае
    // выброса исключения в методе getEmail(). Иначе тест провален)
    public function testEmailException() {

        //ожидание исключения
        //expectExceptionCode(10) -> отлавливает код ошибки
        //expectExceptionMessage("Empty email") -> отлавливает сообщение ошибки
        $this->expectExceptionCode(10);

        $this->user->getEmail();
    }

    //подавление ошибок в тестировании. Оператор управления ошибками(@)
    public function testErrorSuppression(){
        $this->assertFalse(@$this->name("Ильяс"));
    }

    public function name($name){
        if ($name == "Ильяс") {
            trigger_error("Давайте только без Ильясов", E_USER_WARNING);
        }
        return false;
    }

    //тестирование вывода информации
    public function testEcho(){

        $this->expectOutputString("ab");

        //с помощью callback функции можно обработать входную строку
        $this->setOutputCallback(function ($string){
            return trim($string);
        });

        echo "ab ";
    }

    //неполный тест
    public function testIncomplete(){
        $this->markTestIncomplete("Незавершенный");
    }

    //пропущенный тест
    public function testSkipped(){
        $this->markTestSkipped("Пропущенный");
    }

    //тестирование saveUser() с помощью объекта двойника
    public function testSaveUser(){

        //объект тестового двойника
        $db = $this->createMock(\App\Models\DataBase::class);

        //настройка поведения методов для возвращения нужных значений
        $db->expects($this->any())->method('connect')->will($this->returnValue(true));
        //=
        $db->method('query')->willReturn(true);

        $this->assertTrue($this->user->saveUser($db));
    }

    //тестирование вызова метода с определенным параметром
    public function testObserver(){

        //создание заглушки
        $observer = $this->getMockBuilder(\App\Models\UserObserver::class)
            ->setMethodsExcept(['update'])
            ->getMock();

        //настройка поведения методов
        $observer->expects($this->once())->method('update')->with($this->stringContains('upd'));
        //передача заглушки в метод attach(), вызов update()
        $this->user->attach($observer);
        $this->user->update();

    }
}