from django.shortcuts import render
from django.views.generic.list import ListView
from django.views.generic.detail import DetailView  
from django.views.generic.edit import CreateView, UpdateView, DeleteView
from django.urls import reverse_lazy  
from .models import Post  # import the Post model from the models.py file


# Create your views here.
class PostListView(ListView):
    model = Post


class PostCreateView(CreateView):
    model = Post
    fields = "__all__"  # this means that all the fields of the model will be displayed in the form
    success_url = reverse_lazy("blog:all")  # this means that the user will be redirected to the post_list view after creating a new post  
    

class PostDetailView(DetailView):
    model = Post


class PostUpdateView(UpdateView):
    model = Post
    fields = "__all__"
    success_url = reverse_lazy("blog:all")  # this means that the user will be redirected to the post_list view after updating a post


class PostDeleteView(DeleteView):
    model = Post
    fields = "__all__"
    success_url = reverse_lazy("blog:all")  # this means that the user will be redirected to the post_list view after deleting a post