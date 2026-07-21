<?php

namespace Olek\Audit\Identifier;

final class NativeAuditIdGenerator implements AuditIdGeneratorInterface
{
    public function generate(): AuditIdInterface
    {
        // Audit::$id jest kolumną Types::GUID (natywny typ "uuid" na Postgresie) - musi być
        // poprawnym RFC 4122 UUID, zwykły hash (md5/base64) zostanie odrzucony przez bazę.
        $bytes = random_bytes(16);
        $bytes[6] = \chr((\ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = \chr((\ord($bytes[8]) & 0x3f) | 0x80);

        $hex = bin2hex($bytes);

        return new NativeAuditId(sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12),
        ));
    }
}
