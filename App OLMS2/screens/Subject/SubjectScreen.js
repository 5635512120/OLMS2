import React, { Component } from 'react';
import { Container, Header, Content,  Body, Left, Button, Icon, Right, Title, ListItem, View, Grid, Row, Col, Text, Textarea, Spinner } from 'native-base';
import {NavigationActions, } from 'react-navigation';
import {AsyncStorage, StyleSheet, FlatList, Dimensions} from 'react-native';
import News from '../Home/News';
import axios from 'axios';
import moment from 'moment';
import {HOST} from '../host';
export default class SubjectScreen extends React.Component {
  constructor(props){
    super(props);
    this.state={
        username: '',
        password: '',
        data: '',
        name: this.props.navigation.state.params.name,
        code: this.props.navigation.state.params.code,
        comment: '',
        isLoading: false
    }
    this.setUser();
  }

  setUser = async () => {
    this.setState({
        username: await AsyncStorage.getItem('username'),
        password: await AsyncStorage.getItem('password'),
        role : await AsyncStorage.getItem('role'),
    });
    if (this.state.role == 'teacher') {
        this.setState({teacher: true});
    }
    this.checkStatus(this.state.username, this.state.password, this.props.navigation.state.params.id)
    this.handalNews(this.state.username, this.state.password, this.props.navigation.state.params.id);
  }

  checkStatus = async(username, password, id) => {
    const subject = await axios({
      method: 'post',
      url: HOST+'mobileStatusfollow',
        data: {
            username,
            password,
            id
        }
    });
    if(!subject.data.follow) {
      this.setState({follow:'ติดตาม'});
    } else {
      this.setState({follow:'กำลังติดตาม'});
    }
    
  }

  followSubject = async(username, password, id) => {
    const subject = await axios({
      method: 'post',
      url: HOST+'mobileFollowsubject',
        data: {
            username,
            password,
            id
        }
    });
    if(!subject.data.follow) {
      this.setState({follow:'ติดตาม'});
    } else {
      this.setState({follow:'กำลังติดตาม'});
    }
  }

  handalNews = async(username, password, id) => {
    const subject = await axios({
      method: 'post',
      url: HOST+'mobileNews',
        data: {
            username,
            password,
            id
        }
    });
      this.setState({data: await subject.data.subjects});
  }

  setComment = (event) => {
      this.setState({
          comment: event,

      })
  }

  postComment = async(username, password, id, comment) => {
    this.setState({isLoading: true});

    const subject = await axios({
      method: 'post',
      url: HOST+'teacherComment',
        data: {
            username,
            password,
            id,
            comment 
        }
    });
      this.setState({
        data: await subject.data.subjects,
        isLoading: false
      });
  }

  render() {
    const { dispatch, navigate } = this.props.navigation;
    const isLoading = this.state.isLoading ? <Spinner color='#fff' /> : <Text>ประกาศ</Text>;
    const role = this.state.teacher
                  ? <View>
                    <Textarea style={styles.border2} onChangeText={this.setComment} placeholder="อธิบายเพิ่มเติม" />
                    <Button block style={styles.button2} onPress={ () => this.postComment(this.state.username, this.state.password, this.props.navigation.state.params.id, this.state.comment)}>
                        {isLoading}
                    </Button>
                  </View>
                  : <View style={styles.position1}>
                    <Row style={{margin: 5}}>
                        <Col>
                            <Button style={styles.Button} primary onPress={ () => navigate('LeaveformScreen', {name : this.props.navigation.state.params.name, id: this.props.navigation.state.params.id, code: this.props.navigation.state.params.code})}>
                                <Text style={styles.text}>ลาเรียน</Text>
                            </Button>
                        </Col>
                        <Col>
                            <Button style={styles.Button2} bordered onPress={ () => this.followSubject(this.state.username, this.state.password, this.props.navigation.state.params.id)}>
                                <Text style={styles.text}>{this.state.follow}</Text>
                            </Button>
                        </Col>
                    </Row>
                    </View> ;
                
    return (
      <Container>
          <Header >
              <Left>
                <Button transparent onPress={ () => this.props.navigation.goBack()}>
                  <Icon name='arrow-back' />
                </Button>
              </Left>
              <Body>
                <Title>{this.props.navigation.state.params.name}</Title>
              </Body>
              <Right></Right>
          </Header>
            {role}
          <Content>
              <FlatList data = {this.state.data} style={styles.backgroudList}
                  renderItem={({item}) => (
                      <ListItem >
                          <Body>
                              <Text>{this.state.name} ({this.state.code}) <Text note>{moment(item.created_at).fromNow()}</Text></Text>
                              <Text note>{item.comment}</Text>
                          </Body>
                      </ListItem>
                  )
                }
                keyExtractor={(item, index) => index}
              />
          </Content>
      </Container>
    );
  }
}

const {width, height} = Dimensions.get("window");

const styles = StyleSheet.create({
  backgroudList: {
    backgroundColor: 'white',
    margin: 5
  },
  position1: {
    flex: height/7000
  },
  position2: {
    paddingBottom: 75, 
    backgroundColor: 'white'
  },
  Button: {
    width: width/2.1,
    alignSelf: 'flex-start',
    justifyContent: 'center',
  },
  Button2: {
    width: width/2.1,
    alignSelf: 'flex-end',
    justifyContent: 'center',
  },
  text: {
    //margin: width/10,
  },
  border2: {
    width: width / 1.25,
    backgroundColor: '#fff',
    borderRadius: 15,
    justifyContent: 'center',
    alignSelf: 'center',
    marginTop: 10,
    padding: 10
  },
  button2:{
    width: width / 1.25,
    marginTop: 10,
    justifyContent: 'center',
    alignSelf: 'center',
  }
});