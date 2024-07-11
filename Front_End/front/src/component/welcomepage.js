import React from 'react';
import { Typography, Button } from 'antd';
import welcomeImage from '../images/A-1-47.webp';
import './welcomepage.css'
import { Link } from 'react-router-dom';
const { Title } = Typography;

const WelcomePage = () => {
  return (
    <div className="welcome-page-container">
      <div className="welcome-page">
        <div className="header-buttons">
          <Button type="primary" size="large"  style={{ marginRight: '10px' }}>
          <Link to = '/signup'>SignUp</Link>
          </Button>
          <Button type="primary" size="large" >
          <Link to = '/login'>LogIn</Link>
          </Button>
        </div>
        <div className="welcome-content">
          <div className="text">
            <Title level={2} style={{ fontFamily: 'Arial, sans-serif', marginBottom: '20px' }}>
              Welcome to Our Application
            </Title>
          </div>
          <div className="image">
            <img src={welcomeImage} alt="Welcome" className="welcome-image" />
          </div>
        </div>
      </div>
    </div>
  );
};

export default WelcomePage;
