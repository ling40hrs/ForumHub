import { useParams } from 'react-router-dom';

export default function PostPage() {
  const { id } = useParams();
  return (
    <div className="mx-auto max-w-4xl px-4 py-8">
      <h1 className="mb-6 text-2xl font-bold">Post #{id}</h1>
      <p className="text-gray-600">Post detail coming soon.</p>
    </div>
  );
}
