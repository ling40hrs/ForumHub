const BASE = '/api';

export async function api(path, options = {}) {
  const res = await fetch(`${BASE}${path}`, {
    headers: { 'Content-Type': 'application/json', ...options.headers },
    ...options,
  });

  const data = await res.json();

  if (!res.ok) {
    throw new Error(data.error ?? 'Request failed');
  }

  return data;
}

export const get  = (path) => api(path);
export const post = (path, body) => api(path, { method: 'POST', body: JSON.stringify(body) });
export const put  = (path, body) => api(path, { method: 'PUT', body: JSON.stringify(body) });
export const del  = (path) => api(path, { method: 'DELETE' });
