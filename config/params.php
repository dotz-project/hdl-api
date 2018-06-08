<?php

return [
    'version' => getenv("APP_VERSION") ?: '1.0',
    'adminEmail' => getenv("SYS_ADMIN_MAIL"),
    'SECRETKEY' => getenv("SECRETKEY") ?: "r2HeQFYVuw/Rd6LDV4wxnUMEcvotUXS4jLJh+MKRRdp4X9VPk3GFdVJF9mr3TRjaO1gbQDM3+cle4qA+VxZMog==",
    'SECRETIV' => getenv("SECRETIV") ?: "UedE4D1Pc0jXWTK/5daMtg==",
    'SALT' => getenv("SALT") ?: "ArKlBJV4wxnUMEcvotUXS4jLJomM0F",
    'APP_HOST' => getenv('APP_HOST') ?: "http://localhost:8081",
    'API_HOST' => getenv('API_HOST') ?: "http://localhost:8000",
    'SERVER_NAME' => 'localhost',
];
