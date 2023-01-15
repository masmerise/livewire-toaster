<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class ToastHub extends Component
{
    public function __construct(
        private readonly Session $session,
    ) {}

    /** @return array<Toast> */
    public function toasts(): array
    {
        return $this->session->pull(SessionRelay::NAME, []);
    }

    public function render(): View
    {
        return $this->view('toast::hub');
    }
}
