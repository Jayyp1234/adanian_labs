from django.db import models
from django.contrib.auth import get_user_model
from django.utils import timezone
from django.urls import reverse  # to generate URLs for our models
from django.template.defaultfilters import slugify  # to generate a slug for the post

User = get_user_model()


# Create your models here.
class Post(models.Model):
    STATUS_CHOICES = (
        ("draft", "Draft"),
        ("published", "Published"),
    )

    # DB Fields
    title = models.CharField(max_length=250)
    slug = models.SlugField(max_length=300, unique=True, editable=False)
    author = models.ForeignKey(
        User, on_delete=models.CASCADE, related_name="blog_posts"
    )
    body = models.TextField()

    publish = models.DateTimeField(default=timezone.now)
    created = models.DateTimeField(auto_now_add=True)
    updated = models.DateTimeField(auto_now=True)

    status = models.CharField(
        max_length=10, choices=STATUS_CHOICES, default="draft"
    )

    class Meta:
        ordering = ("-publish",)

    def save(self, *args, **kwargs):
        self.slug = slugify(self.title)
        super().save(*args, **kwargs)
        pass

    def __str__(self):
        return self.title

    def get_absolute_url(self):
        return reverse("blog:post_detail", kwargs={
            "slug": self.slug})  # kwargs={"slug": self.slug} means that the slug field will be used to generate the URL
