<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/** @internal */
final class ToastHub extends Component
{
    public function __construct(
        private readonly ToastConfig $config,
        private readonly Session $session,
        private readonly string $view = 'toast::hub',
    ) {}

    public function render(): View
    {
        return $this->view($this->view, [
            'config' => $this->config->toArray(),
            'position' => $this->config->position(),
            'toasts' => $this->session->pull(SessionRelay::NAME, []),
        ]);
    }
}
