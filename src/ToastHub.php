<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/** @internal */
final class ToastHub extends Component
{
    public function __construct(
        private readonly Session $session,
    ) {}

    public function render(): View
    {
        return $this->view('toast::hub', [
            SessionRelay::NAME => $this->session->pull(SessionRelay::NAME, []),
        ]);
    }
}
