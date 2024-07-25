import React from 'react';
import { LockOutlined, UserOutlined } from '@ant-design/icons';
import { Button, Form, Input, message } from 'antd';
import { Link, useNavigate } from 'react-router-dom';
import axios from 'axios';
import styles from './login.css'; // Adjust the path as per your file location

const LoginForm = ({ onRegisterClick }) => {
  const navigate = useNavigate();

  const onFinish = async (values) => {
    try {
      // Sending login request
      const response = await axios.post('http://127.0.0.1:8000/api/login', {
        email: values.username,
        password: values.password,
      });
  
      const { token, role, id } = response.data;
  
      // Store token and client ID in local storage
      localStorage.setItem('token', token);
      localStorage.setItem('clientId', id);
  
      // Navigate based on user role
      if (role === 'client') {
        navigate('/client');
      } else if (role === 'admin') {
        navigate('/admin');
      } else if (role === 'coursier') {
        navigate('/coursier-dashboard');
      } else {
        message.error('Unknown role');
      }
    } catch (error) {
      console.error('Error during login:', error);
      message.error('Invalid email or password');
    }
  };
  

  return (
    <div className={styles['login-form-wrapper']}>
      <Form
        name="normal_login"
        className={styles['login-form']}
        initialValues={{
          remember: true,
        }}
        onFinish={onFinish}
      >
        <Form.Item
          name="username"
          rules={[
            {
              required: true,
              message: 'Please input your Username!',
            },
          ]}
        >
          <Input prefix={<UserOutlined className="site-form-item-icon" />} placeholder="Username" />
        </Form.Item>
        <Form.Item
          name="password"
          rules={[
            {
              required: true,
              message: 'Please input your Password!',
            },
          ]}
        >
          <Input
            prefix={<LockOutlined className="site-form-item-icon" />}
            type="password"
            placeholder="Password"
          />
        </Form.Item>
        <Form.Item>
          <div>
            <label htmlFor="remember_me">
              <input type="checkbox" id="remember_me" name="_remember_me" />
              Remember me
            </label>
          </div>
        </Form.Item>

        <Form.Item>
          <Button type="primary" htmlType="submit" className={styles['login-form-button']}>
            Log in
          </Button>
          <p>
            Or <Link to="/signup" onClick={onRegisterClick}>register now!</Link>
          </p>
        </Form.Item>
      </Form>
    </div>
  );
};

export default LoginForm;
