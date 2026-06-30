import { createContext, useContext, useState, useCallback, useEffect } from 'react';
import { post } from '../lib/fetch';
import { TOKEN_KEY, USER_KEY, UNAUTH_EVENT, AUTH_CHANGE_EVENT } from '../constants/auth';

const AuthContext = createContext(null);

function readStoredUser() {
  const raw = localStorage.getItem(USER_KEY);
  if (!raw) return null;
  try { return JSON.parse(raw); } catch { return null; }
}

export function AuthProvider({ children }) {
  const [user, setUser] = useState(readStoredUser);

  useEffect(() => {
    function onUnauth() { setUser(null); }
    function onAuthChange() { setUser(readStoredUser()); }
    window.addEventListener(UNAUTH_EVENT, onUnauth);
    window.addEventListener(AUTH_CHANGE_EVENT, onAuthChange);
    return () => {
      window.removeEventListener(UNAUTH_EVENT, onUnauth);
      window.removeEventListener(AUTH_CHANGE_EVENT, onAuthChange);
    };
  }, []);

  const login = useCallback(async (email, password) => {
    const data = await post('/auth/login', { email, password });
    localStorage.setItem(TOKEN_KEY, data.token);
    localStorage.setItem(USER_KEY, JSON.stringify(data.user));
    setUser(data.user);
    return data;
  }, []);

  const register = useCallback(async (username, email, password) => {
    const data = await post('/auth/register', { username, email, password });
    localStorage.setItem(TOKEN_KEY, data.token);
    localStorage.setItem(USER_KEY, JSON.stringify(data.user));
    setUser(data.user);
    return data;
  }, []);

  const logout = useCallback(() => {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
    setUser(null);
  }, []);

  return (
    <AuthContext.Provider value={{ user, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export const useAuth = () => {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used within AuthProvider');
  return ctx;
};
