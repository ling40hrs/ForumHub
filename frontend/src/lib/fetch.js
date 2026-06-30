import { TOKEN_KEY, USER_KEY, UNAUTH_EVENT } from '../constants/auth';

const BASE = '/api';

export class ApiError extends Error {
  constructor(message, status, body) {
    super(message);
    this.name = 'ApiError';
    this.status = status;
    this.body = body;
  }
}

export function getToken() {
  return localStorage.getItem(TOKEN_KEY);
}

function clearAuth() {
  localStorage.removeItem(TOKEN_KEY);
  localStorage.removeItem(USER_KEY);
  window.dispatchEvent(new CustomEvent(UNAUTH_EVENT));
}

export async function api(path, options = {}) {
  const token = getToken();
  const headers = {
    'Content-Type': 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    ...options.headers,
  };

  const res = await fetch(`${BASE}${path}`, { ...options, headers });

  if (res.status === 204) return null;

  const body = await res.json().catch(() => ({}));

  if (res.status === 401) {
    clearAuth();
  }

  if (!res.ok) {
    throw new ApiError(body.error ?? 'Request failed', res.status, body);
  }

  return body;
}

export const get  = (path) => api(path);
export const post = (path, body) => api(path, { method: 'POST', body: JSON.stringify(body) });
export const put  = (path, body) => api(path, { method: 'PUT', body: JSON.stringify(body) });
export const del  = (path) => api(path, { method: 'DELETE' });
