const event = (message, type) => {
    dispatchEvent(new CustomEvent('toaster:received', { detail: { message, type }}));
};

const error = message => event(message, 'error');
const info = message => event(message, 'info');
const success = message => event(message, 'success');
const warning = message => event(message, 'warning');

export const Toaster = { error, info, success, warning }
