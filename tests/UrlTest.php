<?php
namespace vakata\http\test;

class UrlTest extends \PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass() {
	}
	public static function tearDownAfterClass() {
	}
	protected function setUp() {
	}
	protected function tearDown() {
	}

	public function testCreate() {
		$url = new \vakata\http\Url('https://user:pass@domain.tld:8080/path/to/file.html?query=string#fragment');
		$this->assertEquals(true, $url instanceof \vakata\http\Url);
		$this->assertEquals('https', $url->getScheme());
		$this->assertEquals('https', $url->scheme);
		$this->assertEquals('domain.tld', $url->getHost());
		$this->assertEquals('domain.tld', $url->host);
		$this->assertEquals('8080', $url->getPort());
		$this->assertEquals('8080', $url->port);
		$this->assertEquals('user', $url->getUser());
		$this->assertEquals('user', $url->user);
		$this->assertEquals('pass', $url->getPass());
		$this->assertEquals('pass', $url->pass);
		$this->assertEquals('/path/to/file.html', $url->getPath());
		$this->assertEquals('/path/to/file.html', $url->path);
		$this->assertEquals('html', $url->getExtension());
		$this->assertEquals('html', $url->getExtension('gif'));
		$this->assertEquals('query=string', $url->getQuery());
		$this->assertEquals('query=string', $url->query);
		$this->assertEquals('fragment', $url->getFragment());
		$this->assertEquals('fragment', $url->fragment);

		$this->assertEquals(['path','to','file.html'], $url->getSegments());
		$this->assertEquals('path', $url->getSegment(0));
		$this->assertEquals('to', $url->getSegment(1));
		$this->assertEquals('file.html', $url->getSegment(2));
		$this->assertEquals('file.html', $url->getSegment(-1));
		$this->assertEquals('file', $url->getSegment(-1, false));
		$this->assertEquals('to', $url->getSegment(-2));
		$this->assertEquals('path', $url->getSegment(-3));
		$this->assertEquals(null, $url->getSegment(5));
		$this->assertEquals('https://user:pass@domain.tld:8080/path/to/file.html?query=string#fragment', (string)$url);
	}
	public function testPartialCreate() {
		$url = new \vakata\http\Url('/path/to/nowhere');
		$this->assertEquals('http', $url->getScheme());
		$this->assertEquals('http', $url->scheme);
		$this->assertEquals('localhost', $url->getHost());
		$this->assertEquals('localhost', $url->host);
		$this->assertEquals(null, $url->getPort());
		$this->assertEquals(null, $url->port);
		$this->assertEquals(null, $url->getUser());
		$this->assertEquals(null, $url->user);
		$this->assertEquals(null, $url->getPass());
		$this->assertEquals(null, $url->pass);
		$this->assertEquals('/path/to/nowhere', $url->getPath());
		$this->assertEquals('/path/to/nowhere', $url->path);
		$this->assertEquals(null, $url->getExtension());
		$this->assertEquals('gif', $url->getExtension('gif'));
		$this->assertEquals(null, $url->getQuery());
		$this->assertEquals(null, $url->query);
		$this->assertEquals(null, $url->getFragment());
		$this->assertEquals(null, $url->fragment);
	}
	public function testSet()
	{
		$url = new \vakata\http\Url('https://user:pass@domain.tld:8080/path/to/file.html?query=string#fragment');
		$url->setScheme('http')->setUser('pass')->setPass('user')->setHost('a.bc')->setPort(81)->setPath('/asdf1')->setQuery('?asdf2')->setFragment('#asdf3');
		$this->assertEquals('http://pass:user@a.bc:81/asdf1?asdf2#asdf3', (string)$url);
	}
	public function testLinks() {
		$url = new \vakata\http\Url('http://domain.tld/path/to/nowhere?query=string#fragment');
		$this->assertEquals('https://domain.tld/path/to/somewhere', $url->linkTo('https://domain.tld/path/to/somewhere'));
		$this->assertEquals('//domain2.tld/path/to/somewhere', $url->linkTo('http://domain2.tld/path/to/somewhere'));
		$this->assertEquals('//domain.tld:8080/path/to/somewhere', $url->linkTo('http://domain.tld:8080/path/to/somewhere'));
		$this->assertEquals('/path/to/somewhere', $url->linkTo('http://domain.tld/path/to/somewhere'));
		$this->assertEquals('./../somewhere', $url->linkTo('http://domain.tld/path/to/somewhere', false));
		$this->assertEquals('?asdf', $url->linkTo('http://domain.tld/path/to/nowhere?asdf'));
		$this->assertEquals('?query=string', $url->linkTo('http://domain.tld/path/to/nowhere?query=string'));
		$this->assertEquals('#asdf', $url->linkTo('http://domain.tld/path/to/nowhere?query=string#asdf'));
		$this->assertEquals('/path/to/nowhere?query=string#fragment', $url->linkFrom('http://domain.tld/path/to/somewhere'));
		$this->assertEquals('./../nowhere?query=string#fragment', $url->linkFrom('http://domain.tld/path/to/somewhere', false));
	}
}
