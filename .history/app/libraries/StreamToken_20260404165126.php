<?php
/**
 * StreamToken – generates a Stream Video user JWT (HS256, no external deps).
 */
class StreamToken {

    /**
     * @param  string $userId  The Stream user ID to embed in the token.
     * @return string  Signed JWT string ready for the Stream Video SDK.
     */
    public static function generate(string $userId): string {
        $secret = STREAM_API_SECRET;
        $now    = time();
        $exp    = $now + (STREAM_TOKEN_TTL_MINUTES * 60);

        $header  = self::b64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = self::b64url(json_encode([
            'iss'     => 'stream-video-php@1.0',
            'sub'     => 'user/' . $userId,
            'user_id' => $userId,
            'iat'     => $now,
            'exp'     => $exp,
        ]));

        $sig = self::b64url(hash_hmac('sha256', "$header.$payload", $secret, true));

        return "$header.$payload.$sig";
    }

    /** Base64-URL encode (RFC 4648 §5, no padding). */
    private static function b64url(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
