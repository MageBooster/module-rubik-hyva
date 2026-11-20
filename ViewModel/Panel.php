<?php
declare(strict_types=1);

namespace MageBooster\RubikHyva\ViewModel;

use MageBooster\Rubik\Model\ConfigProvider;
use MageBooster\Rubik\Model\Settings;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Checkout data of MageBooster_Rubik (the same values the Luma renderer gets from window.checkoutConfig) for the
 * MageBooster Checkout panel.
 */
class Panel implements ArgumentInterface
{
    public const CODE = Settings::CODE;

    /** @var array<string, mixed>|null */
    private ?array $config = null;

    public function __construct(
        private ConfigProvider $configProvider
    ) {
    }

    /** @return array<string, mixed> */
    public function config(): array
    {
        if ($this->config === null) {
            $data = $this->configProvider->getConfig();
            $this->config = (array)($data['payment'][self::CODE] ?? []);
        }
        return $this->config;
    }

    public function isAvailable(): bool
    {
        return $this->config() !== [];
    }

    public function description(): string
    {
        return (string)($this->config()['description'] ?? '');
    }

    public function isTestMode(): bool
    {
        return !empty($this->config()['testMode']);
    }

    /** TRY rate when the order is converted to Turkish lira, else null. */
    public function tryRate(): ?float
    {
        $config = $this->config();
        return !empty($config['convertToTry']) && !empty($config['tryRate']) ? (float)$config['tryRate'] : null;
    }

    public function baseCurrency(): string
    {
        return (string)($this->config()['baseCurrency'] ?? '');
    }
}
