import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { Button, Card, Form, Input, DatePicker, message, Modal } from 'antd';
import moment from 'moment';
import { CheckCircleOutlined, CloseCircleOutlined, ClockCircleOutlined, PlusCircleOutlined, HomeOutlined, EnvironmentOutlined, CalendarOutlined, EditOutlined, DashboardOutlined } from '@ant-design/icons';

const ClientDemandePage = () => {
  const [demandes, setDemandes] = useState([]);
  const [showForm, setShowForm] = useState(false);
  const [form] = Form.useForm();
  const [clientId, setClientId] = useState(null);

  useEffect(() => {
    const storedClientId = localStorage.getItem('clientId');
    if (storedClientId) {
      setClientId(storedClientId);
    }
  }, []);

  useEffect(() => {
    if (clientId) {
      axios.get(`http://127.0.0.1:8000/api/demandes/finddemandesbyclient/${clientId}`)
        .then(response => {
          setDemandes(response.data);
        })
        .catch(error => {
          console.error('Error fetching demandes:', error);
        });
    }
  }, [clientId]);

  const handleFormSubmit = (values) => {
    const newDemande = {
      description: values.description,
      adress_source: values.adress_source,
      adress_dest: values.adress_dest,
      poids: values.poids,
      date_demande: values.date_demande.format('YYYY-MM-DD'),
      status: null,
      date_livraison: null,
      client: { id_client: clientId },
      admin: { id_admin: null },
      coursier: { id_coursier: null }
    };

    axios.post('http://127.0.0.1:8000/api/demandes/add', newDemande)
      .then(response => {
        message.success('Demande added successfully');
        setDemandes([...demandes, response.data]);
        setShowForm(false);
        form.resetFields();
      })
      .catch(error => {
        message.error('Error adding demande');
        console.error('Error:', error);
      });
  };

  const styles = {
    container: {
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'center',
      padding: '20px'
    },
    addButton: {
      marginBottom: '20px',
      backgroundColor: '#4CAF50',
      color: '#fff',
      border: 'none',
      borderRadius: '5px',
      padding: '10px 20px',
      fontSize: '16px',
      cursor: 'pointer',
      transition: 'background-color 0.3s ease',
      display: 'flex',
      alignItems: 'center'
    },
    addButtonIcon: {
      marginRight: '8px'
    },
    demandeList: {
      display: 'flex',
      flexWrap: 'wrap',
      gap: '16px'
    },
    card: {
      width: '300px',
      border: '1px solid #d9d9d9',
      borderRadius: '4px',
      boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
      backgroundColor: '#ffffff',
      transition: 'transform 0.3s ease',
      padding: '15px',
      fontFamily: 'Arial, sans-serif',
      '&:hover': {
        transform: 'scale(1.05)'
      }
    },
    statusIcon: {
      fontSize: '20px',
      marginRight: '8px'
    },
    cardTitle: {
      fontSize: '18px',
      fontWeight: 'bold',
      marginBottom: '10px',
      display: 'flex',
      alignItems: 'center'
    },
    cardDetail: {
      marginBottom: '10px',
      display: 'flex',
      alignItems: 'center',
      fontWeight: 'bold' // Adjust the font weight here
    },
    cardIcon: {
      marginRight: '8px'
    },
    cardValue: {
      fontWeight: 'normal', // Ensure the values have normal font weight
      marginLeft: '8px'
    }
  };

  const getStatusIcon = (status) => {
    switch (status) {
      case 'Completed':
        return <CheckCircleOutlined style={{ ...styles.statusIcon, color: 'green' }} />;
      case 'Cancelled':
        return <CloseCircleOutlined style={{ ...styles.statusIcon, color: 'red' }} />;
      case 'Pending':
        return <ClockCircleOutlined style={{ ...styles.statusIcon, color: 'orange' }} />;
      default:
        return null;
    }
  };

  return (
    <div style={styles.container}>
      <Button type="primary" style={styles.addButton} onClick={() => setShowForm(true)}>
        <PlusCircleOutlined style={styles.addButtonIcon} /> Add New Demande
      </Button>
      <Modal
        title="Add New Demande"
        visible={showForm}
        onCancel={() => setShowForm(false)}
        footer={null}
      >
        <Form
          form={form}
          onFinish={handleFormSubmit}
          layout="vertical"
        >
          <Form.Item
            name="description"
            label={<span><EditOutlined /> Description</span>}
            rules={[{ required: true, message: 'Please input the description!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item
            name="adress_source"
            label={<span><HomeOutlined /> Address Source</span>}
            rules={[{ required: true, message: 'Please input the address source!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item
            name="adress_dest"
            label={<span><EnvironmentOutlined /> Address Destination</span>}
            rules={[{ required: true, message: 'Please input the address destination!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item
            name="poids"
            label={<span><DashboardOutlined /> Weight</span>}
            rules={[{ required: true, message: 'Please input the weight!' }]}
          >
            <Input type="number" step="0.1" />
          </Form.Item>
          <Form.Item
            name="date_demande"
            label={<span><CalendarOutlined /> Date of Request</span>}
            rules={[{ required: true, message: 'Please select the date of request!' }]}
          >
            <DatePicker format="YYYY-MM-DD" />
          </Form.Item>
          <Form.Item>
            <Button type="primary" htmlType="submit">
              Submit
            </Button>
          </Form.Item>
        </Form>
      </Modal>
      <div style={styles.demandeList}>
        {demandes.map(demande => (
          <Card key={demande.id_demande} style={styles.card}>
            <div style={styles.cardTitle}>
              <EditOutlined style={styles.cardIcon} /> Demande ID: <span style={styles.cardValue}>{demande.id_demande}</span>
            </div>
            <div style={styles.cardDetail}>
              <EditOutlined style={styles.cardIcon} /> Description: <span style={styles.cardValue}>{demande.description}</span>
            </div>
            <div style={styles.cardDetail}>
              <HomeOutlined style={styles.cardIcon} /> Address Source: <span style={styles.cardValue}>{demande.adress_source}</span>
            </div>
            <div style={styles.cardDetail}>
              <EnvironmentOutlined style={styles.cardIcon} /> Address Destination: <span style={styles.cardValue}>{demande.adress_dest}</span>
            </div>
            <div style={styles.cardDetail}>
              <DashboardOutlined style={styles.cardIcon} /> Weight: <span style={styles.cardValue}>{demande.poids}</span>
            </div>
            <div style={styles.cardDetail}>
              <CalendarOutlined style={styles.cardIcon} /> Date of Request: <span style={styles.cardValue}>{moment(demande.date_demande).format('YYYY-MM-DD')}</span>
            </div>
            <div style={styles.cardDetail}>
              <ClockCircleOutlined style={styles.cardIcon} /> Status: {getStatusIcon(demande.status)} <span style={styles.cardValue}>{demande.status || 'N/A'}</span>
            </div>
            <div style={styles.cardDetail}>
              <CalendarOutlined style={styles.cardIcon} /> Delivery Date: <span style={styles.cardValue}>{demande.date_livraison ? moment(demande.date_livraison).format('YYYY-MM-DD') : 'N/A'}</span>
            </div>
          </Card>
        ))}
      </div>
    </div>
  );
};

export default ClientDemandePage;
