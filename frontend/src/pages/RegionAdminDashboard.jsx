import React, { useEffect, useState } from "react";

export default function RegionAdminDashboard() {
  const [rtomAdmins, setRtomAdmins] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchRtomAdmins = async () => {
      setLoading(true);
      setError(null);
      try {
        const token = localStorage.getItem('token');
        const response = await fetch("/api/region-admin/rtom-admins", {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
          },
        });
        const data = await response.json();
        if (data.success) {
          setRtomAdmins(data.data);
        } else {
          setError(data.message || 'Failed to fetch RTOM Admins');
        }
      } catch (err) {
        setError('Error fetching RTOM Admins');
      } finally {
        setLoading(false);
      }
    };
    fetchRtomAdmins();
  }, []);

  return (
    <div>
      <h2>Region Admin Dashboard</h2>
      <section>
        <h3>RTOM Admins</h3>
        {loading ? (
          <p>Loading...</p>
        ) : error ? (
          <p style={{ color: 'red' }}>{error}</p>
        ) : rtomAdmins.length === 0 ? (
          <p>No RTOM Admins found for your region.</p>
        ) : (
          <table border="1" cellPadding="8" style={{ borderCollapse: 'collapse', width: '100%' }}>
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>RTOM</th>
                <th>Region</th>
                <th>Created At</th>
              </tr>
            </thead>
            <tbody>
              {rtomAdmins.map(admin => (
                <tr key={admin.id}>
                  <td>{admin.id}</td>
                  <td>{admin.name}</td>
                  <td>{admin.email}</td>
                  <td>{admin.rtom}</td>
                  <td>{admin.region}</td>
                  <td>{admin.created_at ? new Date(admin.created_at).toLocaleString() : ''}</td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </section>
    </div>
  );
}
