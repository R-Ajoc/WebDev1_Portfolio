function formatDateTime(datetimeStr) {
    if (!datetimeStr) return { date: 'Not provided', time: 'Not provided' };
  
    const dateObj = new Date(datetimeStr);
  
    // Format date (e.g., April 19, 2025)
    const date = dateObj.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  
    // Format time (e.g., 2:29 AM)
    const time = dateObj.toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true
    });
  
    return { date, time };
  }

  