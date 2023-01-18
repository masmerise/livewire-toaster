<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/** @internal */
final class ToastHub extends Component
{
    public function __construct(
        private readonly Repository $config,
        private readonly Session $session,
        private readonly string $view = 'toast::hub',
    ) {}

    public function render(): View
    {
        return $this->view($this->view, [
            'position' => Position::from($this->config->get('toast.position')),
            'toasts' => $this->session->pull(SessionRelay::NAME, []),
        ]);
    }
}
