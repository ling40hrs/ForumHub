import { Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import UserMenu from './UserMenu';
import DevUserSwitcher from './DevUserSwitcher';

export default function Navbar() {
  const { user } = useAuth();

  return (
    <header className="border-b border-gray-200 bg-white">
      <div className="mx-auto flex max-w-4xl items-center justify-between px-4 py-3">
        <Link to="/" className="text-lg font-bold text-brand-600">
          ForumHub
        </Link>
        <nav className="flex items-center gap-3 text-sm">
          {user ? (
            <UserMenu user={user} />
          ) : (
            <>
              <Link to="/login" className="text-gray-700 hover:text-brand-600">
                Sign in
              </Link>
              <Link
                to="/register"
                className="rounded-md bg-brand-600 px-3 py-1.5 text-white hover:bg-brand-700"
              >
                Sign up
              </Link>
            </>
          )}
          <DevUserSwitcher />
        </nav>
      </div>
    </header>
  );
}
