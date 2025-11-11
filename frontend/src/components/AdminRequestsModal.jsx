import React, { useState, useEffect } from "react";
import "./AdminRequestsModal.css";
import "bootstrap-icons/font/bootstrap-icons.css";

function AdminRequestsModal({ isOpen, onClose, onAccept, onDecline, onRequestProcessed, callerId }) {
  const [requests, setRequests] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  // Fetch pending requests from localStorage when modal opens
  useEffect(() => {
    if (isOpen) {
      fetchPendingRequests();
    }
  }, [isOpen]);

  const fetchPendingRequests = () => {
    setLoading(true);
    setError(null);
    
    try {
      // Get pending request from localStorage
      const pendingRequest = localStorage.getItem('pendingAdminRequest');
      
      if (pendingRequest) {
        const requestData = JSON.parse(pendingRequest);
        
        // Set the requests (customers array from the request)
        if (requestData.customers && requestData.customers.length > 0) {
          setRequests([{
            id: requestData.requestId,
            customers: requestData.customers,
            customerCount: requestData.customers.length,
            sentDate: requestData.sentDate,
            callerName: requestData.callerName,
            callerId: requestData.callerId
          }]);
        } else {
          setRequests([]);
        }
      } else {
        setRequests([]);
      }
    } catch (err) {
      console.error('Error loading pending requests:', err);
      setError('Failed to load requests. Please try again.');
      setRequests([]);
    } finally {
      setLoading(false);
    }
  };

  const handleAcceptAll = () => {
    if (requests.length === 0) return;
    
    const request = requests[0];
    
    // Convert all customers to the format expected by CallerDashboard
    const allCustomersData = request.customers.map((customer, index) => ({
      id: Date.now() + index, // Ensure unique IDs
      accountNumber: customer.accountNumber,
      name: customer.name,
      contactNumber: customer.contactNumber,
      amountOverdue: customer.amountOverdue,
      daysOverdue: customer.daysOverdue,
      date: customer.date,
      status: "OVERDUE",
      response: "Not Contacted Yet",
      previousResponse: "No previous contact",
      contactHistory: []
    }));
    
    // Store response in localStorage for admin to see
    const response = {
      requestId: request.id,
      status: "ACCEPTED",
      respondedDate: new Date().toLocaleString(),
      reason: null
    };
    localStorage.setItem('callerRequestResponse', JSON.stringify(response));
    
    // Remove the pending request from localStorage
    localStorage.removeItem('pendingAdminRequest');
    
    // Update the sent request in adminSentRequests
    const adminSentRequests = JSON.parse(localStorage.getItem('adminSentRequests') || '[]');
    const updatedRequests = adminSentRequests.map(r => {
      if (r.id === request.id) {
        return {
          ...r,
          status: 'ACCEPTED',
          respondedDate: response.respondedDate
        };
      }
      return r;
    });
    localStorage.setItem('adminSentRequests', JSON.stringify(updatedRequests));
    
    // Call parent handler with all customers at once
    onAccept(allCustomersData);
    
    // Clear all requests
    setRequests([]);
    
    // Notify parent that request is processed
    if (onRequestProcessed) onRequestProcessed();
  };

  const handleDeclineAll = () => {
    if (requests.length === 0) return;
    
    const request = requests[0];
    
    // Store response in localStorage for admin to see
    const response = {
      requestId: request.id,
      status: "DECLINED",
      respondedDate: new Date().toLocaleString(),
      reason: "Caller declined the request"
    };
    localStorage.setItem('callerRequestResponse', JSON.stringify(response));
    
    // Remove the pending request from localStorage
    localStorage.removeItem('pendingAdminRequest');
    
    // Update the sent request in adminSentRequests
    const adminSentRequests = JSON.parse(localStorage.getItem('adminSentRequests') || '[]');
    const updatedRequests = adminSentRequests.map(r => {
      if (r.id === request.id) {
        return {
          ...r,
          status: 'DECLINED',
          respondedDate: response.respondedDate,
          reason: response.reason
        };
      }
      return r;
    });
    localStorage.setItem('adminSentRequests', JSON.stringify(updatedRequests));
    
    // Decline all requests
    requests.forEach(req => {
      onDecline(req.id);
    });
    
    // Clear all requests
    setRequests([]);
    
    // Notify parent that request is processed
    if (onRequestProcessed) onRequestProcessed();
  };

  if (!isOpen) return null;

  return (
    <div className="admin-requests-modal-overlay" onClick={onClose}>
      <div className="admin-requests-modal" onClick={(e) => e.stopPropagation()}>
        <div className="admin-requests-header">
          <div className="header-title">
            <i className="bi bi-inbox-fill"></i>
            <h2>Admin Requests</h2>
            {requests.length > 0 && (
              <span className="requests-count">{requests.length}</span>
            )}
          </div>
          <button className="close-modal-btn" onClick={onClose}>
            <i className="bi bi-x-lg"></i>
          </button>
        </div>

        <div className="admin-requests-body">
          {loading ? (
            <div className="loading-state">
              <i className="bi bi-hourglass-split"></i>
              <p>Loading requests...</p>
            </div>
          ) : error ? (
            <div className="error-state">
              <i className="bi bi-exclamation-triangle"></i>
              <p>{error}</p>
              <button onClick={fetchPendingRequests} className="retry-btn">
                <i className="bi bi-arrow-clockwise"></i>
                Retry
              </button>
            </div>
          ) : requests.length > 0 ? (
            <div className="requests-summary-card">
              <div className="summary-header">
                <i className="bi bi-envelope-open"></i>
                <h3>New Customer Assignment from Admin</h3>
              </div>
              
              <div className="summary-details">
                <div className="detail-item">
                  <span className="detail-label">Total Requests:</span>
                  <span className="detail-value">{requests.length}</span>
                </div>
                <div className="detail-item">
                  <span className="detail-label">Total Customers:</span>
                  <span className="detail-value">
                    {requests.reduce((sum, req) => sum + (req.customerCount || 0), 0)}
                  </span>
                </div>
                <div className="detail-item">
                  <span className="detail-label">Date:</span>
                  <span className="detail-value">{requests[0].sentDate}</span>
                </div>
              </div>

              <div className="summary-actions">
                <button 
                  className="decline-all-btn"
                  onClick={handleDeclineAll}
                  disabled={loading}
                >
                  <i className="bi bi-x-circle"></i>
                  Decline
                </button>
                <button 
                  className="accept-all-btn"
                  onClick={handleAcceptAll}
                  disabled={loading}
                >
                  <i className="bi bi-check-circle"></i>
                  Accept
                </button>
              </div>
            </div>
          ) : (
            <div className="no-requests">
              <i className="bi bi-inbox"></i>
              <h3>No Requests</h3>
              <p>You don't have any pending requests from admin</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

export default AdminRequestsModal;
