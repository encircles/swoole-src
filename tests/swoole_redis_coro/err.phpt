--TEST--
swoole_redis_coro: redis error return
--SKIPIF--
<?php require __DIR__ . '/../include/skipif.inc'; ?>
--FILE--
<?php
require __DIR__ . '/../include/bootstrap.php';
go(function () {
    $redis = new \Swoole\Coroutine\Redis(['timeout' => 3]);
    $redis->connect(REDIS_SERVER_HOST, REDIS_SERVER_PORT);
    $res = $redis->set('foo', 'bar');
    assert($res && $redis->errCode === 0 && $redis->errMsg === '');
    $res = $redis->hIncrBy('foo', 'bar', 123);
    assert(!$res);
    var_dump($redis->errCode, $redis->errMsg);
    $res = $redis->set('foo', 'baz');
    assert($res && $redis->errCode === 0 && $redis->errMsg === '');
});
?>
--EXPECT--
int(2)
string(65) "WRONGTYPE Operation against a key holding the wrong kind of value"
