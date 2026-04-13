<?php
    // View helpers.

    if (!function_exists('getNextTicketId')) {
        function getNextTicketId($tab): string
        {
            $maxId = 0;

            foreach ($tab as $elt) {
                $rawId = (string) ($elt['id'] ?? '');
                if (preg_match('/^TK-(\\d+)$/i', trim($rawId), $matches)) {
                    $numericId = (int) $matches[1];
                    if ($numericId > $maxId) {
                        $maxId = $numericId;
                    }
                }
            }

            return 'TK-' . (string) ($maxId + 1);
        }
    }

    if (!function_exists('getInitials')) {
        function getInitials(?string $name): string
        {
            $parts = preg_split('/\\s+/', trim((string) $name));
            $initials = '';
            foreach ($parts as $part) {
                if ($part !== '' && $initials === '') {
                    $initials .= strtoupper(substr($part, 0, 1));
                    continue;
                }
                if ($part !== '' && strlen($initials) < 2) {
                    $initials .= strtoupper(substr($part, 0, 1));
                }
            }

            return $initials;
        }
    }

    if (!function_exists('getProjectProgressPercent')) {
        function getProjectProgressPercent($contractHours, $usedHours): int
        {
            $contract = (float) $contractHours;
            $used = (float) $usedHours;

            if ($contract <= 0) {
                return 0;
            }

            $percent = (int) round(($used / $contract) * 100);

            if ($percent < 0) {
                return 0;
            }

            if ($percent > 100) {
                return 100;
            }

            return $percent;
        }
    }
?><?php /**PATH C:\Users\coren\Documents\Alternance\Projets\Prod\mini-prod-laravel\app\laravel\resources\views/utils.blade.php ENDPATH**/ ?>