import { Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

export default function UserMenu({ user }) {
  const { logout } = useAuth();

  return (
    <div className="flex items-center gap-3">
      <Link to={`/u/${user.id}`} className="text-gray-700 hover:text-brand-600">
        {user.username}
        {user.karma != null && (
          <span className="ml-1 text-xs text-gray-500">({user.karma})</span>
        )}
      </Link>
      <button
        type="button"
        onClick={logout}
        className="text-gray-700 hover:text-brand-600"
      >
        Log out
      </button>
    </div>
  );
}
