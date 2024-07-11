import React from 'react';

const Agreement = () => {
  const containerStyle = {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    height: '100vh', // 100% of the viewport height
    backgroundColor: '#f0f2f5' // Optional: background color
  };

  const contentStyle = {
    maxWidth: '800px',
    padding: '20px',
    backgroundColor: '#fff', // Optional: background color
    boxShadow: '0 0 10px rgba(0, 0, 0, 0.1)', // Optional: box shadow for better visual
    borderRadius: '8px' // Optional: border radius for rounded corners
  };

  return (
    <div style={containerStyle}>
      <div style={contentStyle}>
        <h1>Agreement</h1>
        <p>
          This is the agreement page. Please read the terms and conditions carefully.
        </p>
        <h2>Terms and Conditions</h2>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at 
          magna nec erat suscipit tristique. Aenean eu orci a libero sodales 
          bibendum. Morbi efficitur velit et nunc vehicula, ac viverra orci 
          varius. Donec in eros sit amet erat condimentum pharetra. Quisque 
          lobortis eros a massa viverra, eget consequat libero tempor. Etiam 
          varius interdum libero, nec interdum lorem sagittis sed. Integer at 
          ipsum vitae orci volutpat gravida. 
        </p>
        <h2>Privacy Policy</h2>
        <p>
          Sed sit amet accumsan nulla. Maecenas interdum tincidunt felis at 
          facilisis. Vestibulum viverra felis eget nisi posuere, id viverra mi 
          mollis. Aenean tincidunt lacus ac turpis ultricies aliquet. Aliquam 
          erat volutpat. Sed elementum ultricies erat, at aliquam mauris 
          malesuada et. Morbi scelerisque magna eget velit fringilla, sed 
          convallis leo rutrum. 
        </p>
        <h2>User Responsibilities</h2>
        <p>
          Curabitur vehicula, leo ut laoreet vehicula, odio lectus dictum lorem, 
          sit amet varius lectus libero eu eros. Donec egestas libero ut justo 
          aliquam, id laoreet nisl porttitor. Nulla vel risus ut arcu efficitur 
          tristique a at lorem. Suspendisse potenti. Fusce non magna eget dui 
          dapibus commodo at non arcu. 
        </p>
      </div>
    </div>
  );
};

export default Agreement;
