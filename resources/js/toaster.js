const event = (message, type) => {
    document.dispatchEvent(new CustomEvent('toaster:received', { detail: { message, type }}));
};

const error = message => event(message, 'error');
const info = message => event(message, 'info');
const success = message => event(message, 'success');
const warning = message => event(message, 'warning');

export { error, info, success, warning }
