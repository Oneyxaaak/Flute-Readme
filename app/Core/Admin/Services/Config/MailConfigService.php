<?php

namespace Flute\Core\Admin\Services\Config;

use Flute\Core\Admin\Support\AbstractConfigService;
use Symfony\Component\HttpFoundation\Response;

class MailConfigService extends AbstractConfigService
{
    public function updateConfig(array $params): Response
    {
        $config = array_merge(config('mail'), [
            "smtp" => $this->b($params['smtpEnabled']),
            "host" => $params['smtpHost'],
            "port" => (int) $params['smtpPort'],
            "from" => $params['smtpFrom'],
            "username" => $params['smtpUsername'],
            "password" => $params['smtpPassword'],
            "secure" => $params['smtpSecure']
        ]);

        try {
            $this->fileSystemService->updateConfig($this->getConfigPath('mail'), $config);
            user()->log('events.config_updated', 'mail');

            return response()->success(__('def.success'));
        } catch (\Exception $e) {
            logs()->error($e);
            return response()->error(500, $e->getMessage() ?? __('def.unknown_error'));
        }
    }
}
