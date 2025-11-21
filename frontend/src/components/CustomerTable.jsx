import React, { useState, useEffect } from "react";
import "./CustomerTable.css";
import API_BASE_URL from "../config/api";

function CustomerTable() {
  const [customers, setCustomers] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchCustomers();
  }, []);

  const fetchCustomers = async () => {
    try {
      setLoading(true);
      const response = await fetch(`${API_BASE_URL}/customers`);
      const result = await response.json();
      
      if (result.success && result.data) {
        // Only show customers with PENDING or assigned status
        const filteredCustomers = result.data.filter(c => 
          c.status === 'PENDING' || c.assignedTo
        );
        setCustomers(filteredCustomers);
      }
      setLoading(false);
    } catch (error) {
      console.error('Error fetching customers:', error);
      setLoading(false);
    }
  };

  const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
  };

  const handleEdit = (customer) => {
    console.log('Edit customer:', customer.name);
    // TODO: Implement edit functionality
    alert(`Edit ${customer.name} - Coming soon!`);
  };

  const handleDelete = async (customer) => {
    if (window.confirm(`Are you sure you want to delete ${customer.name}?`)) {
      try {
        const response = await fetch(`${API_BASE_URL}/customers/${customer._id}`, {
          method: 'DELETE'
        });
        
        if (response.ok) {
          alert('Customer deleted successfully');
          fetchCustomers(); // Refresh the list
        } else {
          alert('Failed to delete customer');
        }
      } catch (error) {
        console.error('Error deleting customer:', error);
        alert('Error deleting customer');
      }
    }
  };

  if (loading) {
    return (
      <div className="table-card">
        <div style={{ textAlign: 'center', padding: '40px', color: '#666' }}>
          <div style={{
            width: '40px',
            height: '40px',
            border: '4px solid #f3f3f3',
            borderTop: '4px solid #1488ee',
            borderRadius: '50%',
            margin: '0 auto 20px',
            animation: 'spin 1s linear infinite'
          }}></div>
          <p>Loading customers...</p>
        </div>
      </div>
    );
  }

  return (
    <>
      <div className="table-card">
        <table className="custom-table">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Account Number</th>
              <th>Contact</th>
              <th>Caller</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {customers.length > 0 ? (
              customers.map((customer) => (
                <tr key={customer._id}>
                  <td>{customer.name}</td>
                  <td>{customer.accountNumber}</td>
                  <td>{customer.contactNumber}</td>
                  <td>{customer.assignedTo ? customer.assignedTo.name : 'Unassigned'}</td>
                  <td>{customer.amountOverdue}</td>
                  <td>{formatDate(customer.assignedDate || customer.createdAt)}</td>
                  <td className="status">
                    <span className={`status-badge ${(customer.status || '').toLowerCase()}`}>
                      {customer.status}
                    </span>
                  </td>
                  <td>
                    <button 
                      onClick={() => handleEdit(customer)}
                      style={{ 
                        background: 'none', 
                        border: 'none', 
                        cursor: 'pointer',
                        marginRight: '10px',
                        color: '#4CAF50',
                        fontSize: '18px'
                      }}
                      title="Edit"
                    >
                      <i className="bi bi-pencil-square"></i>
                    </button>
                    <button 
                      onClick={() => handleDelete(customer)}
                      style={{ 
                        background: 'none', 
                        border: 'none', 
                        cursor: 'pointer',
                        color: '#f44336',
                        fontSize: '18px'
                      }}
                      title="Delete"
                    >
                      <i className="bi bi-trash-fill"></i>
                    </button>
                  </td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="8" style={{ textAlign: 'center', padding: '40px', color: '#999' }}>
                  <i className="bi bi-inbox" style={{ fontSize: '48px', display: 'block', marginBottom: '10px' }}></i>
                  No customers found
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </>
  );
}

export default CustomerTable;
