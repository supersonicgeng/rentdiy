<?php
return [

    //应用ID,您的APPID。
    'app_id' => "2018121862621085",

    'version' => '1.0',

    //商户私钥，您的原始格式RSA私钥
    'merchant_private_key' => "MIIEpAIBAAKCAQEAuFNKQkDv2KL5VVWkFWxmp2ES9Rfj+EMfA/fwI1VTV3iweBksd92TqxJYw3ZxkiUCMmliYpNHbEjFLQaV6O8SnDGNj5h8J0jeQvKRI5ytFSlL8tgSus4qTitMr+VWTKTe6mbiA7hqfmkaKf7yOWX4gtfYgZXviBNR8v82jHKmxFDuCxU6CFByr84RhFJ35aijP+q3xfazKMR7gmvOnHhF/sbiWaLGOl/42rQ2E5UXN0KSHToAnZpKPfQdEFqQKo0iW5zfsQoiWzCOlEEZBXLjbnu8CZ0JnIZ9gRjDXm5IsAinkWDW8JOX3ERLAW1ih89hp/+aw4tCZPpj6lmZ5PjTtQIDAQABAoIBAQCWusWG2ENKDDuIJLhBLJvlU+SEuDybz4eVXzLoMeYtKWxlSXCrtG4E/sPHUxwcPldFkhf6NCW7zuYuo6wDBz1YrMzuF+uu1E+sv18gmAaRv2tz8m2ehOjjc1UKoaeolUoUtH0uPagYJypWHT1G4rQNiyRpE4sE44eoBjs9LqRg88lmGT85MirZu0HUMKxtzEZr8V8mX7BGLrvW9tPJ/cqbqopQWuG73PU5YGBRA/HxFNucgvF9qei8Q6Pq51bJoQmppItZ10x6rPnFhVAMLBTh2oyB4XxxtDGlofZyJiyeICO+ZqG7egMd12lAegkcirnvY2G/uZmbSNcJplC1yDUBAoGBAOV7grL0tL52dL8Ok6McHJXCZnpXsTqXm4k5kEeywsfxokDheZdoqkUnWlEk7dgL9+lfpOAfA+qNkjmIUnyL/iI9Qmga6G8hia99xrLWDZiiqoCYIyqMAzl+qC0Z6pCC3mWltQh71cBb5SglEjbgmZjX8P7J5bUViq0pwUPNPkBpAoGBAM2f9C3LpLQNcwYIhBehzOvIr4+EXXAhuxHSm89dL6SGy0Gc+Zh9G9g0G5imLY6hr7bpYih6e3CqP0P0M6IeAVx4eNy1zIcT7VFqBQ22C144FQhn0TzxU5iR4WCOcBU/Lf5dMg3OTID78h00ncVlxK0inRFupNuWu7Y0ltWr/09tAoGAW6ENdtcutD4oL4Fqgd5fq4yLzp3lLjgK6qgJbBd8nslkt/NP0Z0BH8uuzGeqcHGW86A0/ShlL+qHUGGWThS3zIQZV+gmlvkVOPVHuXCuzRtmaSJWKE7vmq1wCVInzrvygTPBLToFB2GqBnWG6FKWAel3WMTU65FGZg4eFSyGhsECgYAOI0NMqsXGQ253Kbq7TTqjs4aunXE3NnqAPTTNSyDtdP8gocRfgaqacOq80iArwF4ue1luYw19r5bt+ypZqSp6yyW0NyO63dnhUSLL0IpzjfOUQl8Wi5kt0knms6RtMYzeAlZgsKB99dStwWJfoiGlrc2S10KqfnCllHQSeOLy+QKBgQC3TjjcmqOA+VBQE6E2/nzFOYkCnA57ejUy18F0sd5mK+HsqOWAoZFVSrCXQpev0RdA7cI0Joo2tY4JpjCjnv4t2cCu92VxjGnXSRMm28+grVPitqT0Tybkx01S0E3v3DUwY1VZjFjX7EyB9OmweJjz+OMO75w9inseCzgel2bcGQ==",

    //异步通知地址
    'notify_url' => "",
    //http://工程公网访问地址/alipay.trade.wap.pay-PHP-UTF-8/notify_url.php

    //同步跳转
    'return_url' => "",
    //http://mitsein.com/alipay.trade.wap.pay-PHP-UTF-8/return_url.php
    // jk.mrwangqi.com

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjWfTTBCwPBMhTHMHkT6PneflsMH3/PqJPSMVBqIYAFsorrz7rncM8F+d1bPG9wLtKvtzXfWTOB+u1uPYcTrl/Ia1zK0siYJxWkqxDeUqYopZvEoAXKm9UtiRoIZPuh6y3Q9MxWr3lrqIxpxuVzePWCU9c47bLvL5s/1ZQuM2iFSp1RWXL6LjWQuk9UyuMzzOleTuh5all6v3Mi00EFxxK31n0L7jFeqTMj5TMMoBnxWbEF4fPmZWuVha9qMZ5R88tCeHDnQ/NelsNAInz0gbCPlFA6urzvcd1ClOZoICcT6N4bLsstuCZE770FFOn9p/PXalAdPcycVfaxFUIYvrxQIDAQAB",
];
