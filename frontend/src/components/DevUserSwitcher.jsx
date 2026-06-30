import { useState } from 'react';
import { TOKEN_KEY, USER_KEY, AUTH_CHANGE_EVENT } from '../constants/auth';

const FAKE_USERS = [
  { id: 1, username: 'alice', email: 'alice@test.com', avatar: null, karma: 42 },
  { id: 2, username: 'bob',   email: 'bob@test.com',   avatar: null, karma: 12 },
];

function emit(user) {
  if (user) {
    localStorage.setItem(TOKEN_KEY, 'dev-fake-token');
    localStorage.setItem(USER_KEY, JSON.stringify(user));
  } else {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
  }
  window.dispatchEvent(new CustomEvent(AUTH_CHANGE_EVENT));
}

function DevUserSwitcherInner() {
  const [open, setOpen] = useState(false);
  const active = localStorage.getItem(USER_KEY);

  return (
    <div className="relative">
      <button
        type="button"
        onClick={() => setOpen((o) => !o)}
        className="rounded border border-amber-400 bg-amber-50 px-2 py-1 text-xs font-medium text-amber-800 hover:bg-amber-100"
        title="Dev only — fake user switcher"
      >
        Dev
      </button>
      {open && (
        <div className="absolute right-0 z-10 mt-1 w-44 rounded border border-gray-200 bg-white py-1 shadow">
          {FAKE_USERS.map((u) => (
            <button
              key={u.id}
              type="button"
              onClick={() => { emit(u); setOpen(false); }}
              className="block w-full px-3 py-1.5 text-left text-sm text-gray-700 hover:bg-gray-50"
            >
              {u.username} (karma {u.karma})
              {active && JSON.parse(active).id === u.id && <span className="ml-1 text-amber-600">active</span>}
            </button>
          ))}
          <button
            type="button"
            onClick={() => { emit(null); setOpen(false); }}
            className="block w-full border-t border-gray-100 px-3 py-1.5 text-left text-sm text-red-600 hover:bg-gray-50"
          >
            Log out
          </button>
        </div>
      )}
    </div>
  );
}

export default function DevUserSwitcher() {
  if (!import.meta.env.DEV) return null;
  return <DevUserSwitcherInner />;
}
