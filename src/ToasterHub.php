<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/** @internal */
final class ToasterHub extends Component
{
    public const NAME = 'toaster-hub';

    public function __construct(
        private readonly ToasterConfig $config,
        private readonly Session $session,
        private readonly string $view = 'toaster::hub',
    ) {}

    public function render(): View
    {
        return $this->view($this->view, [
            'alignment' => $this->config->alignment(),
            'closeable' => $this->config->wantsCloseableToasts,
            'config' => $this->config->toJavaScript(),
            'position' => $this->config->position(),
            'toasts' => $this->session->pull(SessionRelay::NAME, []),
        ]);
    }
}
