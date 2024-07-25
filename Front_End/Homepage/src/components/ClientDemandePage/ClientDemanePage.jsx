import React, { useState, useEffect } from 'react';
import { Card, Row, Col, Typography, Space, Button, Modal, Form, Input, DatePicker, message } from 'antd';
import { CheckCircleOutlined, ClockCircleOutlined } from '@ant-design/icons';
import DemandeService from './DemandeService';
import moment from 'moment';

const { Title, Text } = Typography;

const ClientDemanePage = () => {
  const [demandes, setDemandes] = useState([]);
  const [loading, setLoading] = useState(false);
  const [isModalVisible, setIsModalVisible] = useState(false);
  const [selectedDemande, setSelectedDemande] = useState(null);
  const [form] = Form.useForm();

  useEffect(() => {
    fetchDemandes();
  }, []);

  const fetchDemandes = async () => {
    setLoading(true);
    try {
      const response = await DemandeService.getAllDemandes();
      setDemandes(response.data);
    } catch (error) {
      console.error('Failed to fetch demandes', error);
      message.error('Failed to fetch demandes');
    } finally {
      setLoading(false);
    }
  };

  const handleEdit = (demande) => {
    setSelectedDemande(demande);
    form.setFieldsValue({
      description: demande.description,
      adress_source: demande.adress_source,
      adress_dest: demande.adress_dest,
      poids: demande.poids,
      date_demande: demande.date_demande ? moment(demande.date_demande) : null,
      date_livraison: demande.date_livraison ? moment(demande.date_livraison) : null,
    });
    setIsModalVisible(true);
  };

  const handleDelete = async (id) => {
    try {
      await DemandeService.deleteDemande(id);
      fetchDemandes();
    } catch (error) {
      console.error('Failed to delete demande', error);
      message.error('Failed to delete demande');
    }
  };

  const handleModalOk = async () => {
    try {
      const values = await form.validateFields();
      if (selectedDemande) {
        await DemandeService.updateDemande(selectedDemande.id_demande, values);
        message.success('Demande updated successfully');
      } else {
        await DemandeService.createDemande({ ...values, status: 'pending' });
        message.success('Demande created successfully');
      }
      setIsModalVisible(false);
      fetchDemandes();
      form.resetFields();
      setSelectedDemande(null);
    } catch (error) {
      console.error('Failed to save demande', error);
      message.error('Failed to save demande');
    }
  };

  const handleModalCancel = () => {
    setIsModalVisible(false);
    form.resetFields();
    setSelectedDemande(null);
  };

  return (
    <div style={{ padding: '20px' }}>
      <Title level={2}>Client Demandes</Title>
      <Button type="primary" onClick={() => setIsModalVisible(true)} style={{ marginBottom: '20px' }}>
        Add Demande
      </Button>
      <Row gutter={[16, 16]}>
        {demandes.map((demande) => (
          <Col xs={24} sm={12} md={8} lg={6} key={demande.id_demande}>
            <Card
              title={
                <span>
                  Demande +{demande.id_demande}
                  {demande.status === 'completed' ? (
                    <CheckCircleOutlined style={{ color: 'green', marginLeft: '10px' }} />
                  ) : (
                    <ClockCircleOutlined style={{ color: 'orange', marginLeft: '10px' }} />
                  )}
                </span>
              }
              bordered={false}
            >
              <Space direction="vertical">
                <Text strong>Description:</Text>
                <Text>{demande.description}</Text>
                <Text strong>Address Source:</Text>
                <Text>{demande.adress_source}</Text>
                <Text strong>Address Destination:</Text>
                <Text>{demande.adress_dest}</Text>
                <Text strong>Weight:</Text>
                <Text>{demande.poids}</Text>
                <Text strong>Date Demande:</Text>
                <Text>{moment(demande.date_demande).format('YYYY-MM-DD')}</Text>
                <Text strong>Date Livraison:</Text>
                <Text>{demande.date_livraison ? moment(demande.date_livraison).format('YYYY-MM-DD') : 'N/A'}</Text>
                <Space>
                  <Button type="primary" onClick={() => handleEdit(demande)}>Edit</Button>
                  <Button danger onClick={() => handleDelete(demande.id_demande)}>Delete</Button>
                </Space>
              </Space>
            </Card>
          </Col>
        ))}
      </Row>

      <Modal
        title={selectedDemande ? 'Edit Demande' : 'Add Demande'}
        visible={isModalVisible}
        onOk={handleModalOk}
        onCancel={handleModalCancel}
        okText="Save"
      >
        <Form form={form} layout="vertical">
          <Form.Item
            name="description"
            label="Description"
            rules={[{ required: true, message: 'Please input the description' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item name="adress_source" label="Address Source">
            <Input />
          </Form.Item>
          <Form.Item name="adress_dest" label="Address Destination">
            <Input />
          </Form.Item>
          <Form.Item name="poids" label="Weight">
            <Input type="number" />
          </Form.Item>
          <Form.Item name="date_demande" label="Request Date">
            <DatePicker />
          </Form.Item>
          <Form.Item name="date_livraison" label="Delivery Date">
            <DatePicker />
          </Form.Item>
        </Form>
      </Modal>
    </div>
  );
};

export default ClientDemanePage;
